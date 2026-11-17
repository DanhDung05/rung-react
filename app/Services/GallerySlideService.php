<?php

namespace App\Services;

use App\Models\GallerySlide;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Throwable;

class GallerySlideService
{
    private const SLIDE_DIR = 'media/gallery/the-window';
    private const FULL_DIR = 'media/gallery/the-window/full';
    private const SLIDE_WIDTH = 660;
    private const SLIDE_HEIGHT = 900;
    private const MAX_FULL_EDGE = 2400;

    /**
     * Tạo slide mới: xử lý file trước, ghi DB sau, lỗi thì dọn file.
     */
    public function create(UploadedFile $file, array $data): GallerySlide
    {
        $processed = $this->processUpload($file, $data['title'] ?? '', (int) ($data['crop_x'] ?? 50), (int) ($data['crop_y'] ?? 50));

        try {
            return DB::transaction(function () use ($processed, $data) {
                $sortOrder = (int) GallerySlide::query()->max('sort_order') + 1;

                return GallerySlide::create([
                    'title'        => $data['title'],
                    'category'     => $data['category'],
                    'accent'       => $data['accent'] ?? null,
                    'description'  => $data['description'] ?? null,
                    'slide_path'   => $processed['slide_path'],
                    'full_path'    => $processed['full_path'],
                    'placeholder'  => $processed['placeholder'],
                    'crop_x'       => $processed['crop_x'],
                    'crop_y'       => $processed['crop_y'],
                    'full_width'   => $processed['full_width'],
                    'full_height'  => $processed['full_height'],
                    'sort_order'   => $sortOrder,
                    'is_active'    => (bool) ($data['is_active'] ?? true),
                ]);
            });
        } catch (Throwable $e) {
            $this->deleteFiles($processed['slide_path'], $processed['full_path']);
            throw $e;
        }
    }

    /**
     * Cập nhật slide. Có ảnh mới: xử lý ảnh mới. Không có ảnh nhưng đổi tâm crop:
     * tái sinh slide/LQIP từ ảnh gốc đã lưu.
     */
    public function update(GallerySlide $slide, ?UploadedFile $file, array $data): GallerySlide
    {
        $oldSlidePath = $slide->slide_path;
        $oldFullPath = $slide->full_path;
        $processed = null;

        if ($file) {
            $processed = $this->processUpload($file, $data['title'] ?? '', (int) ($data['crop_x'] ?? 50), (int) ($data['crop_y'] ?? 50));
        } elseif ((int) $slide->crop_x !== (int) ($data['crop_x'] ?? 50) || (int) $slide->crop_y !== (int) ($data['crop_y'] ?? 50)) {
            $processed = $this->regenerateSlide($slide, (int) ($data['crop_x'] ?? 50), (int) ($data['crop_y'] ?? 50));
        }

        $payload = [
            'title'        => $data['title'],
            'category'     => $data['category'],
            'accent'       => $data['accent'] ?? null,
            'description'  => $data['description'] ?? null,
            'is_active'    => (bool) ($data['is_active'] ?? true),
        ];

        if ($processed) {
            $payload = array_merge($payload, [
                'slide_path'  => $processed['slide_path'],
                'full_path'   => $processed['full_path'],
                'placeholder' => $processed['placeholder'],
                'crop_x'      => $processed['crop_x'],
                'crop_y'      => $processed['crop_y'],
                'full_width'  => $processed['full_width'],
                'full_height' => $processed['full_height'],
            ]);
        } else {
            $payload['crop_x'] = (int) ($data['crop_x'] ?? 50);
            $payload['crop_y'] = (int) ($data['crop_y'] ?? 50);
        }

        try {
            DB::transaction(fn () => $slide->update($payload));
        } catch (Throwable $e) {
            if ($processed) {
                $this->deleteFiles($processed['slide_path'], $processed['full_path']);
            }
            throw $e;
        }

        if ($processed) {
            if ($processed['slide_path'] !== $oldSlidePath) {
                $this->deleteFileIfUnused($oldSlidePath);
            }
            if ($processed['full_path'] !== $oldFullPath) {
                $this->deleteFileIfUnused($oldFullPath);
            }
        }

        return $slide->refresh();
    }

    /**
     * Xoá DB trước, xoá file sau; file lỗi chỉ log.
     */
    public function delete(GallerySlide $slide): void
    {
        $slidePath = $slide->slide_path;
        $fullPath = $slide->full_path;

        $slide->delete();

        $this->deleteFileIfUnused($slidePath);
        $this->deleteFileIfUnused($fullPath);
    }

    /**
     * Đổi chỗ sort_order với slide liền kề (up/down).
     */
    public function move(GallerySlide $slide, string $direction): void
    {
        DB::transaction(function () use ($slide, $direction) {
            $ordered = GallerySlide::query()->ordered()->lockForUpdate()->get();
            $index = $ordered->search(fn (GallerySlide $item) => $item->id === $slide->id);

            if ($index === false) {
                return;
            }

            $targetIndex = $direction === 'up' ? $index - 1 : $index + 1;
            if (! isset($ordered[$targetIndex])) {
                return;
            }

            $other = $ordered[$targetIndex];
            $currentOrder = $slide->sort_order;

            $slide->update(['sort_order' => $other->sort_order]);
            $other->update(['sort_order' => $currentOrder]);
        });
    }

    /**
     * Xử lý ảnh upload: EXIF orientation → kiểm tra kích thước → full (resize 2400) → slide 660x900 → LQIP.
     *
     * @return array<string, mixed>
     */
    private function processUpload(UploadedFile $file, string $title, int $cropX, int $cropY): array
    {
        $path = $file->getRealPath();
        $mime = (string) $file->getMimeType();

        try {
            $image = (new ImageManager(new Driver()))->read($path);
        } catch (Throwable) {
            throw ValidationException::withMessages([
                'image' => 'File tải lên không phải ảnh hợp lệ hoặc không đọc được.',
            ]);
        }

        // Intervention tự áp dụng EXIF orientation khi read() (config autoOrientation=true).

        $width = $image->width();
        $height = $image->height();

        if ($width < self::SLIDE_WIDTH || $height < self::SLIDE_HEIGHT) {
            throw ValidationException::withMessages([
                'image' => sprintf(
                    'Ảnh quá nhỏ: cần tối thiểu %dx%d px sau khi xoay (ảnh hiện %dx%d).',
                    self::SLIDE_WIDTH,
                    self::SLIDE_HEIGHT,
                    $width,
                    $height
                ),
            ]);
        }

        $slug = Str::limit(Str::slug($title) ?: 'gallery-slide', 60, '');
        $random = Str::lower(Str::random(8));
        $baseName = $slug . '-' . $random;

        $fullImage = clone $image;
        $fullImage->scaleDown(width: self::MAX_FULL_EDGE, height: self::MAX_FULL_EDGE);

        $fullExtension = $this->fullExtension($mime);
        $fullPath = self::FULL_DIR . '/' . $baseName . '.' . $fullExtension;
        $slidePath = self::SLIDE_DIR . '/' . $baseName . '.webp';

        $slideImage = $this->makeSlide($image, $cropX, $cropY);
        $placeholder = $this->makePlaceholder($slideImage);

        try {
            Storage::disk('public')->put($fullPath, $this->encodeFull($fullImage, $fullExtension));
            Storage::disk('public')->put($slidePath, (string) $slideImage->toWebp(quality: 82));
        } catch (Throwable $e) {
            $this->deleteFiles($slidePath, $fullPath);
            throw $e;
        }

        return [
            'slide_path'  => $slidePath,
            'full_path'   => $fullPath,
            'placeholder' => $placeholder,
            'crop_x'      => $cropX,
            'crop_y'      => $cropY,
            'full_width'  => $fullImage->width(),
            'full_height' => $fullImage->height(),
        ];
    }

    /**
     * Tái sinh slide/LQIP từ ảnh full đã lưu (khi chỉ đổi tâm crop).
     *
     * @return array<string, mixed>
     */
    private function regenerateSlide(GallerySlide $slide, int $cropX, int $cropY): array
    {
        if (! Storage::disk('public')->exists($slide->full_path)) {
            throw ValidationException::withMessages([
                'image' => 'Không tìm thấy ảnh gốc đã lưu để tái sinh ảnh slide.',
            ]);
        }

        try {
            $image = (new ImageManager(new Driver()))->read(Storage::disk('public')->path($slide->full_path));
        } catch (Throwable) {
            throw ValidationException::withMessages([
                'image' => 'Ảnh gốc đã lưu không đọc được.',
            ]);
        }

        if ($image->width() < self::SLIDE_WIDTH || $image->height() < self::SLIDE_HEIGHT) {
            throw ValidationException::withMessages([
                'image' => 'Ảnh gốc đã lưu nhỏ hơn 660x900, không thể tạo ảnh slide.',
            ]);
        }

        $slideImage = $this->makeSlide($image, $cropX, $cropY);
        $slug = Str::limit(Str::slug($slide->title) ?: 'gallery-slide', 60, '');
        $slidePath = self::SLIDE_DIR . '/' . $slug . '-' . Str::lower(Str::random(8)) . '.webp';

        try {
            Storage::disk('public')->put($slidePath, (string) $slideImage->toWebp(quality: 82));
        } catch (Throwable $e) {
            throw $e;
        }

        return [
            'slide_path'  => $slidePath,
            'full_path'   => $slide->full_path,
            'placeholder' => $this->makePlaceholder($slideImage),
            'crop_x'      => $cropX,
            'crop_y'      => $cropY,
            'full_width'  => $slide->full_width,
            'full_height' => $slide->full_height,
        ];
    }

    private function makeSlide(ImageInterface $image, int $cropX, int $cropY): ImageInterface
    {
        $width = $image->width();
        $height = $image->height();
        $scale = max(self::SLIDE_WIDTH / $width, self::SLIDE_HEIGHT / $height);
        $resizeWidth = (int) ceil($width * $scale);
        $resizeHeight = (int) ceil($height * $scale);

        $image = $image->resize($resizeWidth, $resizeHeight);

        $x = (int) round((max(0, $resizeWidth - self::SLIDE_WIDTH)) * (max(0, min(100, $cropX)) / 100));
        $y = (int) round((max(0, $resizeHeight - self::SLIDE_HEIGHT)) * (max(0, min(100, $cropY)) / 100));

        return $image->crop(self::SLIDE_WIDTH, self::SLIDE_HEIGHT, $x, $y);
    }

    private function makePlaceholder(ImageInterface $slide): string
    {
        $encoded = (clone $slide)->scaleDown(width: 40)->toWebp(quality: 60)->toString();

        return 'data:image/webp;base64,' . base64_encode($encoded);
    }

    private function fullExtension(string $mime): string
    {
        return match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };
    }

    private function encodeFull(ImageInterface $image, string $extension): string
    {
        return match ($extension) {
            'png' => (string) $image->toPng(),
            'webp' => (string) $image->toWebp(quality: 85),
            default => (string) $image->toJpeg(quality: 85),
        };
    }

    private function deleteFiles(string $slidePath, string $fullPath): void
    {
        $this->deleteFileIfUnused($slidePath);
        $this->deleteFileIfUnused($fullPath);
    }

    /**
     * Chỉ xoá file nằm trong thư mục gallery và không còn record khác trỏ tới.
     */
    private function deleteFileIfUnused(string $path): void
    {
        if (! str_starts_with($path, self::SLIDE_DIR . '/')) {
            return;
        }

        if (GallerySlide::query()->where('slide_path', $path)->orWhere('full_path', $path)->exists()) {
            return;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (Throwable $e) {
            Log::warning('Không xoá được file gallery slide: ' . $path . ' — ' . $e->getMessage());
        }
    }
}
