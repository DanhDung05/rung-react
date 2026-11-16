<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Import bộ ảnh "The Window" vào Media (collection gallery) để dùng cho section Thư viện.
 * Chạy lại an toàn: bỏ qua file đã có theo file_path.
 */
class ImportTheWindowGallery extends Command
{
    protected $signature = 'media:import-the-window';

    protected $description = 'Import ảnh The Window vào Media (thư viện trang chủ)';

    public function handle(): int
    {
        $dir = 'media/gallery/the-window';
        $metaPath = storage_path('app/public/' . $dir . '/meta.json');

        if (! file_exists($metaPath)) {
            $this->error('Thiếu meta.json: ' . $metaPath);

            return self::FAILURE;
        }

        $items = json_decode(file_get_contents($metaPath), true) ?: [];
        $created = 0;

        foreach ($items as $index => $item) {
            $file = $item['file'] ?? null;
            if (! $file) {
                continue;
            }

            $path = $dir . '/' . $file;
            if (! Storage::disk('public')->exists($path)) {
                $this->warn("Thiếu file: {$path}");
                continue;
            }

            if (Media::where('file_path', $path)->exists()) {
                continue;
            }

            Media::create([
                'collection' => 'gallery',
                'file_name'  => $file,
                'file_path'  => $path,
                'mime_type'  => 'image/webp',
                'file_size'  => Storage::disk('public')->size($path),
                'alt_text'   => $item['title'] ?? null,
                'caption'    => $item['category'] ?? null,
                'sort_order' => $index,
            ]);

            $created++;
        }

        $this->info("Đã import {$created} ảnh vào Media (collection gallery).");

        return self::SUCCESS;
    }
}
