<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    /**
     * Upload 1 ảnh — trả về path tương đối.
     */
    public function upload(UploadedFile $file, string $folder, int $thumbSize = 400): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename  = Str::random(20) . '.' . $extension;

        $year  = now()->format('Y');
        $month = now()->format('m');
        $path  = "media/{$folder}/{$year}/{$month}";

        $storedPath = $file->storeAs($path, $filename, 'public');

        if ($thumbSize > 0) {
            $this->createThumbnail($storedPath, $thumbSize);
        }

        return $storedPath;
    }

    /**
     * Xóa file vật lý.
     */
    public function delete(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $thumbPath = $this->getThumbPath($path);
        if (Storage::disk('public')->exists($thumbPath)) {
            Storage::disk('public')->delete($thumbPath);
        }
    }

    /**
     * Thay ảnh cũ bằng ảnh mới.
     */
    public function replace(UploadedFile $newFile, ?string $oldPath, string $folder, int $thumbSize = 400): string
    {
        $newPath = $this->upload($newFile, $folder, $thumbSize);

        if ($oldPath) {
            $this->delete($oldPath);
        }

        return $newPath;
    }

    /**
     * Tạo thumbnail từ ảnh gốc.
     */
    protected function createThumbnail(string $path, int $size): void
    {
        try {
            $fullPath = Storage::disk('public')->path($path);

            if (!file_exists($fullPath)) {
                return;
            }

            $manager = new ImageManager(new Driver());
            $image   = $manager->read($fullPath);
            $image->scaleDown(width: $size);

            $thumbPath = $this->getThumbPath($path);
            $thumbFull = Storage::disk('public')->path($thumbPath);

            $thumbDir = dirname($thumbFull);
            if (!is_dir($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }

            $image->save($thumbFull);
        } catch (\Throwable $e) {
            \Log::warning('Không tạo được thumbnail: ' . $e->getMessage());
        }
    }

    /**
     * Sinh đường dẫn thumbnail từ path gốc.
     */
    protected function getThumbPath(string $path): string
    {
        $info = pathinfo($path);
        return $info['dirname'] . '/thumbs/' . $info['basename'];
    }
}