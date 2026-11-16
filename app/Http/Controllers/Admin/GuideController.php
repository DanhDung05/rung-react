<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * Trang Trợ giúp Admin: render Markdown từ docs/ (nguồn duy nhất).
 */
class GuideController extends Controller
{
    public function index(Request $request)
    {
        $manifest = config('admin_docs', []);
        $docsPath = realpath(base_path('docs'));

        $docs = collect($manifest)->map(fn ($doc, $slug) => [
            'slug'        => $slug,
            'title'       => $doc['title'],
            'group'       => $doc['group'] ?? 'Tài liệu',
            'description' => $doc['description'] ?? '',
        ])->values()->all();

        $slug = (string) $request->query('doc', 'admin-guide');
        if (! isset($manifest[$slug])) {
            $slug = 'admin-guide';
        }

        $file = realpath(base_path($manifest[$slug]['file']));
        if (! $docsPath || ! $file || ! str_starts_with($file, $docsPath . DIRECTORY_SEPARATOR)) {
            abort(404);
        }

        $markdown = file_get_contents($file);
        $markdown = $this->rewriteDocLinks($markdown, $manifest);

        [$headings] = $this->extractHeadings($markdown);

        $html = Str::markdown($markdown, [
            'html_input'         => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $html = $this->injectHeadingIds($html, $headings);

        $slugs = array_keys($manifest);
        $position = array_search($slug, $slugs, true);

        return Inertia::render('Admin/Guide/Index', [
            'docs'    => $docs,
            'current' => [
                'slug'     => $slug,
                'title'    => $manifest[$slug]['title'],
                'group'    => $manifest[$slug]['group'] ?? 'Tài liệu',
                'html'     => $html,
                'headings' => $headings,
            ],
            'previous' => $position > 0 ? $docs[$position - 1] : null,
            'next'     => $position !== false && $position < count($docs) - 1 ? $docs[$position + 1] : null,
        ]);
    }

    /**
     * Đổi link .md nội bộ thành link trang Trợ giúp.
     */
    private function rewriteDocLinks(string $markdown, array $manifest): string
    {
        $map = [];
        foreach ($manifest as $slug => $doc) {
            $map[basename($doc['file'])] = $slug;
        }

        return preg_replace_callback(
            '/\]\(([^)]+\.md)(#[^)]*)?\)/',
            function ($matches) use ($map) {
                $base = basename($matches[1]);

                return isset($map[$base])
                    ? '](/admin/huong-dan?doc=' . $map[$base] . ($matches[2] ?? '') . ')'
                    : $matches[0];
            },
            $markdown
        );
    }

    /**
     * Lấy heading (##, ###) và slug duy nhất.
     *
     * @return array{0: array<int, array{level:int,title:string,id:string}>}
     */
    private function extractHeadings(string $markdown): array
    {
        $headings = [];
        $used = [];
        $inCode = false;

        foreach (preg_split('/\r?\n/', $markdown) as $line) {
            if (preg_match('/^```/', $line)) {
                $inCode = ! $inCode;
                continue;
            }

            if ($inCode) {
                continue;
            }

            if (preg_match('/^(#{2,3})\s+(.+)$/', $line, $match)) {
                $title = trim(preg_replace('/[*_`]/', '', $match[2]));
                $base = Str::slug($title) ?: 'muc';
                $id = $base;
                $counter = 2;

                while (in_array($id, $used, true)) {
                    $id = $base . '-' . $counter++;
                }

                $used[] = $id;
                $headings[] = ['level' => strlen($match[1]), 'title' => $title, 'id' => $id];
            }
        }

        return [$headings];
    }

    /**
     * Gắn id vào thẻ h2/h3 theo thứ tự để mục lục hoạt động.
     */
    private function injectHeadingIds(string $html, array $headings): string
    {
        $index = 0;

        return preg_replace_callback('/<h([23])>(.*?)<\/h\1>/s', function ($matches) use (&$index, $headings) {
            $heading = $headings[$index] ?? null;
            $index++;

            if (! $heading) {
                return $matches[0];
            }

            return '<h' . $matches[1] . ' id="' . e($heading['id']) . '">' . $matches[2] . '</h' . $matches[1] . '>';
        }, $html);
    }
}
