<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

/**
 * Chuyển dữ liệu design token legacy sang key chuẩn (idempotent).
 *
 * - bg_primary      → palette_cream
 * - bg_alternate    → palette_white
 * - theme_h1..h3_color → typo_h1..h3_color
 *
 * Chỉ ghi khi key đích trống. Không xóa key cũ.
 */
class MigrateDesignTokens extends Command
{
    protected $signature = 'tokens:migrate {--dry-run : Chỉ hiển thị, không ghi dữ liệu}';

    protected $description = 'Chuyển Settings màu legacy sang design token chuẩn (an toàn, idempotent)';

    public function handle(): int
    {
        $map = [
            ['bg_primary', 'palette_cream', 'palette', 'color'],
            ['bg_alternate', 'palette_white', 'palette', 'color'],
            ['theme_h1_color', 'typo_h1_color', 'typography', 'color'],
            ['theme_h2_color', 'typo_h2_color', 'typography', 'color'],
            ['theme_h3_color', 'typo_h3_color', 'typography', 'color'],
        ];

        $dryRun = (bool) $this->option('dry-run');
        $changed = 0;

        foreach ($map as [$from, $to, $group, $type]) {
            $source = Setting::get($from);
            $target = Setting::get($to);

            if (! $source) {
                $this->line("SKIP {$from} → {$to}: nguồn trống");
                continue;
            }

            if ($target) {
                $this->line("SKIP {$from} → {$to}: đích đã có giá trị ({$target})");
                continue;
            }

            $this->line(($dryRun ? 'WOULD ' : 'SET ') . "{$from} ({$source}) → {$to}");

            if (! $dryRun) {
                Setting::set($to, $source, $group, $type);
            }

            $changed++;
        }

        $this->info(($dryRun ? '[dry-run] ' : '') . "Đã xử lý {$changed} key.");

        return self::SUCCESS;
    }
}
