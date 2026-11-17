<?php

namespace App\Console\Commands;

use App\Helpers\CacheHelper;
use App\Models\GallerySlide;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Import 20 slide "The Window" từ manifest JSON vào bảng gallery_slides.
 *
 * Manifest được tạo một lần từ project gốc (assets/gallery-data.js) và nằm
 * ngoài Git tại storage/app/private/the-window/gallery-data.json.
 */
class ImportTheWindowGallerySlides extends Command
{
    protected $signature = 'gallery:import-the-window
        {--manifest= : Đường dẫn manifest JSON (mặc định storage/app/private/the-window/gallery-data.json)}';

    protected $description = 'Import slide The Window từ manifest JSON vào gallery_slides';

    private const SLIDE_DIR = 'media/gallery/the-window';

    public function handle(): int
    {
        $manifestPath = $this->option('manifest')
            ?: storage_path('app/private/the-window/gallery-data.json');

        if (! is_file($manifestPath)) {
            $this->error('Không tìm thấy manifest: ' . $manifestPath);
            $this->line('Tạo manifest JSON từ The Window/assets/gallery-data.js hoặc truyền --manifest=/duong/dan/gallery-data.json');

            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($manifestPath), true);

        if (! is_array($data) || ! isset($data['works'], $data['stories'], $data['originalImageSizes']) || ! is_array($data['works'])) {
            $this->error('Manifest không hợp lệ: cần works, stories, originalImageSizes.');

            return self::FAILURE;
        }

        if (count($data['works']) === 0) {
            $this->error('Manifest không có slide nào trong works.');

            return self::FAILURE;
        }

        $rows = [];
        $missing = [];

        foreach ($data['works'] as $index => $work) {
            if (! is_array($work) || count($work) < 5) {
                $this->error("works[{$index}] không đúng 5 phần tử [title, category, slide, placeholder, full].");

                return self::FAILURE;
            }

            [$title, $category, $slide, $placeholder, $full] = array_values($work);

            $slidePath = self::SLIDE_DIR . '/' . basename((string) $slide);
            $fullPath = self::SLIDE_DIR . '/full/' . basename((string) $full);

            if (! Storage::disk('public')->exists($slidePath)) {
                $missing[] = $slidePath;
            }

            if (! Storage::disk('public')->exists($fullPath)) {
                $missing[] = $fullPath;
            }

            $story = $data['stories'][$full] ?? [];
            $size = $data['originalImageSizes'][$full] ?? null;

            if (! is_array($size) || count($size) < 2) {
                $absolute = Storage::disk('public')->path($fullPath);
                $detected = is_file($absolute) ? @getimagesize($absolute) : false;
                $size = $detected ? [$detected[0], $detected[1]] : null;
            }

            if (! is_array($size) || count($size) < 2) {
                $this->error("Không xác định được kích thước ảnh gốc cho works[{$index}]: {$full}");

                return self::FAILURE;
            }

            $rows[] = [
                'title'        => (string) $title,
                'category'     => (string) $category,
                'accent'       => $story['accent'] ?? null,
                'description'  => $story['description'] ?? null,
                'slide_path'   => $slidePath,
                'full_path'    => $fullPath,
                'placeholder'  => (string) ($placeholder ?? ''),
                'full_width'   => (int) $size[0],
                'full_height'  => (int) $size[1],
                'sort_order'   => (int) $index,
                'is_active'    => true,
            ];
        }

        if ($missing !== []) {
            $this->error('Thiếu file ảnh trong storage/app/public:');
            foreach (array_unique($missing) as $path) {
                $this->line('  - ' . $path);
            }

            return self::FAILURE;
        }

        $created = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $slide = GallerySlide::firstOrNew(['slide_path' => $row['slide_path']]);
            $exists = $slide->exists;
            $slide->fill($row)->save();

            $exists ? $updated++ : $created++;
        }

        CacheHelper::clearGalleryCarousel();

        $this->info("Import xong: {$created} tạo mới, {$updated} cập nhật, tổng " . GallerySlide::count() . ' slide.');
        $this->line('Manifest: ' . $manifestPath);

        return self::SUCCESS;
    }
}
