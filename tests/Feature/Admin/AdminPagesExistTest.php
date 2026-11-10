<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

/**
 * Nhóm F — Bảo đảm KHÔNG còn trang Admin nào rơi về GenericPage.
 *
 * Quét toàn bộ controller Admin tìm `Inertia::render('Admin/...')` và kiểm tra
 * file component tương ứng tồn tại trong resources/js/Pages.
 */
class AdminPagesExistTest extends TestCase
{
    public function test_moi_trang_admin_deu_co_react_component(): void
    {
        $components = [];

        foreach (glob(app_path('Http/Controllers/Admin/*.php')) as $file) {
            $source = file_get_contents($file);

            if (preg_match_all("/Inertia::render\(\s*'(Admin\/[^']+)'/", $source, $matches)) {
                foreach ($matches[1] as $component) {
                    $components[$component] = basename($file);
                }
            }
        }

        ksort($components);

        $this->assertNotEmpty($components, 'Không tìm thấy Inertia::render nào trong controller Admin.');

        $missing = [];

        foreach ($components as $component => $controller) {
            $path = resource_path('js/Pages/' . $component . '.jsx');

            if (! is_file($path)) {
                $missing[] = "{$component} ({$controller})";
            }
        }

        $this->assertSame([], $missing, 'Thiếu component React: ' . implode(', ', $missing));

        // 59 trang Admin theo kiểm kê (58 trang + Dashboard).
        $this->assertGreaterThanOrEqual(59, count($components), 'Số trang Admin ít hơn kiểm kê.');
    }

    public function test_khong_component_admin_nao_dung_generic_page(): void
    {
        $offenders = [];

        foreach (glob(resource_path('js/Pages/Admin/*/*.jsx')) as $file) {
            if (str_contains(file_get_contents($file), 'GenericPage')) {
                $offenders[] = str_replace(resource_path('js/Pages/'), '', $file);
            }
        }

        $this->assertSame([], $offenders, 'Trang Admin còn dùng GenericPage: ' . implode(', ', $offenders));
    }
}
