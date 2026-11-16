<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Đọc CHANGELOG.md làm nguồn duy nhất cho trang Lịch sử cập nhật.
 */
class ChangelogService
{
    private const GROUP_LABELS = [
        'added'      => 'Thêm',
        'changed'    => 'Thay đổi',
        'fixed'      => 'Sửa lỗi',
        'security'   => 'Bảo mật',
        'deprecated' => 'Sắp bỏ',
        'removed'    => 'Đã bỏ',
        'docs'       => 'Tài liệu',
        'notes'      => 'Ghi chú',
    ];

    public function __construct(private ?string $path = null)
    {
    }

    private function filePath(): string
    {
        return $this->path ?: base_path('CHANGELOG.md');
    }

    /**
     * @return array<int, array{version: string, label: string, date: ?string, title: string, groups: array<int, array{key: string, label: string, items: array<int, string>}>}>
     */
    public function entries(): array
    {
        $path = $this->filePath();

        if (! is_file($path) || ! is_readable($path)) {
            Log::warning('Không đọc được CHANGELOG.md', ['path' => $path]);

            return [];
        }

        clearstatcache(true, $path);
        $mtime = @filemtime($path) ?: 0;
        $cacheKey = 'changelog:' . md5($path) . ':' . $mtime;

        try {
            return Cache::remember($cacheKey, 3600, fn () => $this->parse((string) file_get_contents($path)));
        } catch (\Throwable $e) {
            Log::warning('Parse CHANGELOG.md thất bại', ['path' => $path, 'error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * @return array<int, array{version: string, label: string, date: ?string, title: string, groups: array<int, array{key: string, label: string, items: array<int, string>}>}>
     */
    private function parse(string $content): array
    {
        $lines = preg_split('/\r?\n/', $content) ?: [];
        $entries = [];
        $current = null;
        $groupIndex = null;
        $used = [];

        $finalize = function () use (&$current, &$entries, &$used) {
            if (! $current) {
                return;
            }

            $base = $current['version'];
            $used[$base] = ($used[$base] ?? 0) + 1;
            if ($used[$base] > 1) {
                $current['version'] = $base . '-' . $used[$base];
            }

            $entries[] = $current;
            $current = null;
        };

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (preg_match('/^##\s+(.+)$/', $trimmed, $match)) {
                $finalize();
                $current = $this->parseHeading($match[1]);
                $groupIndex = null;
                continue;
            }

            if (! $current) {
                continue;
            }

            if (preg_match('/^###\s+(.+)$/', $trimmed, $match)) {
                $key = strtolower(trim($match[1]));
                $current['groups'][] = [
                    'key'   => $key,
                    'label' => self::GROUP_LABELS[$key] ?? $match[1],
                    'items' => [],
                ];
                $groupIndex = count($current['groups']) - 1;
                continue;
            }

            if ($groupIndex !== null && preg_match('/^\s*[-*]\s+(.+)$/', $line, $match)) {
                $current['groups'][$groupIndex]['items'][] = trim($match[1]);
            }
        }

        $finalize();

        return $entries;
    }

    private function parseHeading(string $heading): array
    {
        $clean = trim($heading, " \t[]");
        $version = null;
        $date = null;

        if (preg_match('/\bv?(\d+\.\d+\.\d+)\b/', $clean, $match)) {
            $version = $match[1];
        }

        if (preg_match('/\b(\d{4}-\d{2}-\d{2})\b/', $clean, $match)) {
            $date = $match[1];
        }

        $title = preg_replace('/\[?\d{4}-\d{2}-\d{2}\]?/', '', $clean);
        $title = preg_replace('/\bv?\d+\.\d+\.\d+\b/', '', (string) $title);
        $title = trim((string) $title, " \t-–—:|");

        if (! $version) {
            $version = $date ?: 'unknown';
        }

        if (! $date) {
            $date = date('Y-m-d', @filemtime($this->filePath()) ?: time());
        }

        return [
            'version' => $version,
            'label'   => preg_match('/^\d+\.\d+\.\d+$/', $version) ? 'v' . $version : $date,
            'date'    => $date,
            'title'   => $title !== '' ? $title : 'Bản cập nhật ' . $date,
            'groups'  => [],
        ];
    }
}
