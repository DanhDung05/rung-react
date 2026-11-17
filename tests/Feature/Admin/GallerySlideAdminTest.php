<?php

namespace Tests\Feature\Admin;

use App\Helpers\CacheHelper;
use App\Models\GallerySlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GallerySlideAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Cache::flush();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function member(string $role = 'user'): User
    {
        return User::factory()->create(['role' => $role, 'is_active' => true]);
    }

    /**
     * JPEG with hai nửa trái đỏ / phải xanh để kiểm tra tâm crop.
     */
    private function wideJpeg(int $width = 3000, int $height = 2000): string
    {
        $image = imagecreatetruecolor($width, $height);
        $red = imagecolorallocate($image, 220, 30, 30);
        $blue = imagecolorallocate($image, 30, 30, 220);
        imagefilledrectangle($image, 0, 0, (int) ($width / 2) - 1, $height, $red);
        imagefilledrectangle($image, (int) ($width / 2), 0, $width, $height, $blue);
        ob_start();
        imagejpeg($image, null, 90);
        $data = (string) ob_get_clean();
        imagedestroy($image);

        return $data;
    }

    private function upload(string $name, string $data): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, $data);
    }

    /**
     * Ảnh JPEG 1800x2400 (dọc) gắn EXIF Orientation=6 → hiển thị 2400x1800.
     */
    private function rotatedJpeg(): string
    {
        $image = imagecreatetruecolor(1800, 2400);
        $color = imagecolorallocate($image, 120, 160, 120);
        imagefill($image, 0, 0, $color);
        ob_start();
        imagejpeg($image, null, 90);
        $jpeg = (string) ob_get_clean();
        imagedestroy($image);

        $tiff = "II\x2A\x00" . pack('V', 8)
            . pack('v', 1)
            . pack('vvV', 0x0112, 3, 1)
            . pack('v', 6) . "\x00\x00"
            . pack('V', 0);
        $app1 = "\xFF\xE1" . pack('n', strlen("Exif\x00\x00" . $tiff) + 2) . "Exif\x00\x00" . $tiff;

        return substr($jpeg, 0, 2) . $app1 . substr($jpeg, 2);
    }

    private function averageColor(string $absolutePath): array
    {
        $image = imagecreatefromwebp($absolutePath);
        $width = imagesx($image);
        $height = imagesy($image);
        $totals = [0, 0, 0];
        $count = 0;

        for ($x = 0; $x < $width; $x += 40) {
            for ($y = 0; $y < $height; $y += 40) {
                $color = imagecolorat($image, $x, $y);
                $totals[0] += ($color >> 16) & 0xFF;
                $totals[1] += ($color >> 8) & 0xFF;
                $totals[2] += $color & 0xFF;
                $count++;
            }
        }

        imagedestroy($image);

        return [
            'r' => $totals[0] / max(1, $count),
            'g' => $totals[1] / max(1, $count),
            'b' => $totals[2] / max(1, $count),
        ];
    }

    private function storePayload(array $overrides = []): array
    {
        return array_merge([
            'title'       => 'Tour thử nghiệm',
            'category'    => 'Miền Bắc / Trekking',
            'accent'      => 'Một góc nhìn',
            'description' => 'Mô tả ảnh thử nghiệm.',
            'crop_x'      => 50,
            'crop_y'      => 50,
            'is_active'   => 1,
        ], $overrides);
    }

    public function test_admin_can_create_slide_and_generate_expected_files(): void
    {
        $admin = $this->admin();
        $payload = $this->storePayload([
            'title' => 'Hồ trên núi',
            'image' => $this->upload('wide.jpg', $this->wideJpeg()),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.gallery-slides.store'), $payload)
            ->assertRedirect(route('admin.gallery-slides.index'))
            ->assertSessionHas('success');

        $slide = GallerySlide::firstOrFail();
        $disk = Storage::disk('public');

        $this->assertSame(660, getimagesize($disk->path($slide->slide_path))[0]);
        $this->assertSame(900, getimagesize($disk->path($slide->slide_path))[1]);
        $this->assertSame(2400, $slide->full_width);
        $this->assertSame(1600, $slide->full_height);
        $this->assertSame([2400, 1600], array_slice(getimagesize($disk->path($slide->full_path)), 0, 2));
        $this->assertStringStartsWith('data:image/webp;base64,', $slide->placeholder);
        $this->assertSame(1, $slide->sort_order);
        $this->assertTrue($slide->is_active);
    }

    public function test_crop_left_center_right_produce_different_slides(): void
    {
        $admin = $this->admin();
        $colors = [];

        foreach ([0, 50, 100] as $cropX) {
            $this->actingAs($admin)->post(route('admin.gallery-slides.store'), $this->storePayload([
                'title'  => 'Crop ' . $cropX,
                'crop_x' => $cropX,
                'image'  => $this->upload('wide.jpg', $this->wideJpeg()),
            ]))->assertRedirect();

            $slide = GallerySlide::query()->latest('id')->firstOrFail();
            $colors[$cropX] = [
                'hash'  => md5_file(Storage::disk('public')->path($slide->slide_path)),
                'color' => $this->averageColor(Storage::disk('public')->path($slide->slide_path)),
            ];
        }

        $this->assertNotSame($colors[0]['hash'], $colors[50]['hash']);
        $this->assertNotSame($colors[50]['hash'], $colors[100]['hash']);
        $this->assertGreaterThan($colors[0]['color']['b'], $colors[0]['color']['r']);
        $this->assertGreaterThan($colors[100]['color']['r'], $colors[100]['color']['b']);
    }

    public function test_crop_offsets_match_css_object_position(): void
    {
        $admin = $this->admin();
        $samples = [];

        foreach ([0, 50, 100] as $cropX) {
            $this->actingAs($admin)->post(route('admin.gallery-slides.store'), $this->storePayload([
                'title'  => 'Object position ' . $cropX,
                'crop_x' => $cropX,
                'image'  => $this->upload('position.jpg', $this->wideJpeg()),
            ]))->assertRedirect();

            $slide = GallerySlide::query()->latest('id')->firstOrFail();
            $image = imagecreatefromwebp(Storage::disk('public')->path($slide->slide_path));
            $samples[$cropX] = [
                'left' => imagecolorat($image, 50, 450),
                'right' => imagecolorat($image, 610, 450),
            ];
            imagedestroy($image);
        }

        $red = static fn (int $color): bool => (($color >> 16) & 0xFF) > 180 && (($color) & 0xFF) < 80;
        $blue = static fn (int $color): bool => (($color) & 0xFF) > 180 && (($color >> 16) & 0xFF) < 80;

        $this->assertTrue($red($samples[0]['left']));
        $this->assertTrue($red($samples[0]['right']));
        $this->assertTrue($blue($samples[100]['left']));
        $this->assertTrue($blue($samples[100]['right']));
        $this->assertTrue($red($samples[50]['left']));
        $this->assertTrue($blue($samples[50]['right']));
        $this->assertNotSame($samples[0]['left'], $samples[100]['left']);
    }

    public function test_transparent_png_keeps_png_alpha_in_full_file(): void
    {
        $image = imagecreatetruecolor(1000, 1200);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);
        $opaque = imagecolorallocatealpha($image, 220, 30, 30, 0);
        imagefilledrectangle($image, 200, 300, 800, 900, $opaque);
        ob_start();
        imagepng($image);
        $png = (string) ob_get_clean();
        imagedestroy($image);

        $this->actingAs($this->admin())->post(route('admin.gallery-slides.store'), $this->storePayload([
            'title' => 'PNG trong suốt',
            'image' => $this->upload('transparent.png', $png),
        ]))->assertRedirect();

        $slide = GallerySlide::firstOrFail();
        $this->assertStringEndsWith('.png', $slide->full_path);
        $this->assertTrue(Storage::disk('public')->exists($slide->full_path));

        $full = imagecreatefrompng(Storage::disk('public')->path($slide->full_path));
        $alpha = (imagecolorat($full, 10, 10) >> 24) & 0x7F;
        imagedestroy($full);

        $this->assertGreaterThan(0, $alpha);
    }

    public function test_exif_orientation_is_applied_before_dimensions(): void
    {
        $this->actingAs($this->admin())->post(route('admin.gallery-slides.store'), $this->storePayload([
            'title' => 'Ảnh xoay EXIF',
            'image' => $this->upload('rotated.jpg', $this->rotatedJpeg()),
        ]))->assertRedirect();

        $slide = GallerySlide::firstOrFail();

        $this->assertSame(2400, $slide->full_width);
        $this->assertSame(1800, $slide->full_height);
        $this->assertSame([2400, 1800], array_slice(getimagesize(Storage::disk('public')->path($slide->full_path)), 0, 2));
        $this->assertSame([660, 900], array_slice(getimagesize(Storage::disk('public')->path($slide->slide_path)), 0, 2));

        $savedExif = @exif_read_data(Storage::disk('public')->path($slide->full_path));
        $this->assertTrue($savedExif === false || empty($savedExif['Orientation']));
        $this->assertTrue($savedExif === false || empty($savedExif['GPS']));
    }

    public function test_too_small_image_is_rejected_with_clear_message(): void
    {
        $this->actingAs($this->admin())->post(route('admin.gallery-slides.store'), $this->storePayload([
            'image' => $this->upload('small.jpg', $this->wideJpeg(500, 500)),
        ]))->assertSessionHasErrors(['image' => 'Ảnh quá nhỏ: cần tối thiểu 660x900 px sau khi xoay (ảnh hiện 500x500).']);

        $this->assertDatabaseCount('gallery_slides', 0);
    }

    public function test_non_image_and_spoofed_extension_are_rejected(): void
    {
        $this->actingAs($this->admin())->post(route('admin.gallery-slides.store'), $this->storePayload([
            'image' => $this->upload('not-image.jpg', 'day khong phai anh'),
        ]))->assertSessionHasErrors('image');

        // PNG thật nhưng đổi đuôi .jpg → mimes phải từ chối.
        $png = imagecreatetruecolor(800, 800);
        ob_start();
        imagepng($png);
        $pngData = (string) ob_get_clean();
        imagedestroy($png);

        $this->actingAs($this->admin())->post(route('admin.gallery-slides.store'), $this->storePayload([
            'image' => $this->upload('spoof.jpg', $pngData),
        ]))->assertSessionHasErrors('image');

        $this->assertDatabaseCount('gallery_slides', 0);
    }

    public function test_editing_crop_without_new_image_regenerates_slide_only(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('admin.gallery-slides.store'), $this->storePayload([
            'title' => 'Crop lại',
            'image' => $this->upload('wide.jpg', $this->wideJpeg()),
        ]))->assertRedirect();

        $slide = GallerySlide::firstOrFail();
        $oldSlide = $slide->slide_path;
        $oldFull = $slide->full_path;
        $oldFullHash = md5_file(Storage::disk('public')->path($oldFull));
        $oldSlideHash = md5_file(Storage::disk('public')->path($oldSlide));

        $this->actingAs($admin)->patch(route('admin.gallery-slides.update', $slide), [
            'title'       => 'Crop lại',
            'category'    => 'Miền Bắc / Trekking',
            'description' => 'Đổi tâm crop.',
            'crop_x'      => 100,
            'crop_y'      => 50,
            'is_active'   => 1,
        ])->assertRedirect();

        $slide->refresh();

        $this->assertSame($oldFull, $slide->full_path);
        $this->assertNotSame($oldSlide, $slide->slide_path);
        $this->assertFalse(Storage::disk('public')->exists($oldSlide));
        $this->assertSame($oldFullHash, md5_file(Storage::disk('public')->path($slide->full_path)));
        $this->assertNotSame($oldSlideHash, md5_file(Storage::disk('public')->path($slide->slide_path)));
        $this->assertSame(100, $slide->crop_x);
    }

    public function test_replacing_image_removes_old_files_only_after_success(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('admin.gallery-slides.store'), $this->storePayload([
            'image' => $this->upload('first.jpg', $this->wideJpeg()),
        ]))->assertRedirect();

        $slide = GallerySlide::firstOrFail();
        $oldSlide = $slide->slide_path;
        $oldFull = $slide->full_path;

        $this->actingAs($admin)->patch(route('admin.gallery-slides.update', $slide), $this->storePayload([
            'title' => 'Ảnh mới',
            'image' => $this->upload('second.jpg', $this->wideJpeg(2400, 1800)),
        ]))->assertRedirect();

        $slide->refresh();

        $this->assertNotSame($oldSlide, $slide->slide_path);
        $this->assertNotSame($oldFull, $slide->full_path);
        $this->assertFalse(Storage::disk('public')->exists($oldSlide));
        $this->assertFalse(Storage::disk('public')->exists($oldFull));
        $this->assertTrue(Storage::disk('public')->exists($slide->slide_path));
        $this->assertTrue(Storage::disk('public')->exists($slide->full_path));
    }

    public function test_delete_removes_database_and_physical_files(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('admin.gallery-slides.store'), $this->storePayload([
            'image' => $this->upload('delete.jpg', $this->wideJpeg()),
        ]))->assertRedirect();

        $slide = GallerySlide::firstOrFail();
        $slidePath = $slide->slide_path;
        $fullPath = $slide->full_path;

        $this->actingAs($admin)
            ->delete(route('admin.gallery-slides.destroy', $slide))
            ->assertRedirect();

        $this->assertDatabaseMissing('gallery_slides', ['id' => $slide->id]);
        $this->assertFalse(Storage::disk('public')->exists($slidePath));
        $this->assertFalse(Storage::disk('public')->exists($fullPath));
    }

    public function test_admin_can_open_index_create_and_edit_pages(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.gallery-slides.store'), $this->storePayload([
            'title' => 'Trang quản trị',
            'image' => $this->upload('admin.jpg', $this->wideJpeg()),
        ]))->assertRedirect(route('admin.gallery-slides.index'));

        $slide = GallerySlide::firstOrFail();

        $this->actingAs($admin)->get(route('admin.gallery-slides.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/GallerySlides/Index')
                ->has('slides.data', 1)
                ->where('slides.data.0.title', 'Trang quản trị'));

        $this->actingAs($admin)->get(route('admin.gallery-slides.create'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Admin/GallerySlides/Create'));

        $this->actingAs($admin)->get(route('admin.gallery-slides.edit', $slide))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/GallerySlides/Edit')
                ->where('slide.id', $slide->id)
                ->where('slide.crop_x', 50));
    }

    public function test_non_admin_cannot_open_gallery_admin_pages(): void
    {
        $editor = $this->member('editor');

        $this->actingAs($editor)->get(route('admin.gallery-slides.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.gallery-slides.create'))->assertForbidden();

        auth()->logout();
        $this->get(route('admin.gallery-slides.index'))->assertRedirect(route('login'));
    }
    public function test_only_admin_can_access_carousel_admin_routes(): void
    {
        $guest = $this->post(route('admin.gallery-slides.store'), []);
        $guest->assertRedirect(route('login'));

        $this->actingAs($this->member())
            ->post(route('admin.gallery-slides.store'), [])
            ->assertForbidden();

        $this->actingAs($this->member('editor'))
            ->post(route('admin.gallery-slides.store'), [])
            ->assertForbidden();
    }

    public function test_move_up_down_swaps_sort_order_in_transaction(): void
    {
        $admin = $this->admin();
        foreach (['Một', 'Hai'] as $title) {
            $this->actingAs($admin)->post(route('admin.gallery-slides.store'), $this->storePayload([
                'title' => $title,
                'image' => $this->upload('move.jpg', $this->wideJpeg()),
            ]))->assertRedirect();
        }

        $first = GallerySlide::query()->ordered()->firstOrFail();
        $second = GallerySlide::query()->ordered()->skip(1)->firstOrFail();
        $firstOrder = $first->sort_order;
        $secondOrder = $second->sort_order;

        $this->actingAs($admin)->patch(route('admin.gallery-slides.move', [$first, 'down']))->assertRedirect();

        $this->assertSame($secondOrder, $first->fresh()->sort_order);
        $this->assertSame($firstOrder, $second->fresh()->sort_order);
    }

    public function test_update_metadata_without_new_image_keeps_existing_files(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('admin.gallery-slides.store'), $this->storePayload([
            'image' => $this->upload('keep.jpg', $this->wideJpeg()),
        ]))->assertRedirect();

        $slide = GallerySlide::firstOrFail();
        $slidePath = $slide->slide_path;
        $fullPath = $slide->full_path;
        $slideHash = md5_file(Storage::disk('public')->path($slidePath));
        $fullHash = md5_file(Storage::disk('public')->path($fullPath));

        $this->actingAs($admin)->patch(route('admin.gallery-slides.update', $slide), [
            'title'       => 'Chỉ đổi tiêu đề',
            'category'    => 'Miền Nam / Biển đảo',
            'accent'      => 'Giữ nguyên ảnh',
            'description' => 'Không upload ảnh mới.',
            'crop_x'      => 50,
            'crop_y'      => 50,
            'is_active'   => 0,
        ])->assertRedirect();

        $slide->refresh();

        $this->assertSame($slidePath, $slide->slide_path);
        $this->assertSame($fullPath, $slide->full_path);
        $this->assertSame($slideHash, md5_file(Storage::disk('public')->path($slidePath)));
        $this->assertSame($fullHash, md5_file(Storage::disk('public')->path($fullPath)));
        $this->assertSame('Chỉ đổi tiêu đề', $slide->title);
        $this->assertFalse($slide->is_active);
    }

    public function test_model_hook_clears_carousel_cache(): void
    {
        Cache::put(CacheHelper::GALLERY_CAROUSEL_KEY, 'stale', 600);

        $this->actingAs($this->admin())->post(route('admin.gallery-slides.store'), $this->storePayload([
            'image' => $this->upload('cache.jpg', $this->wideJpeg()),
        ]))->assertRedirect();

        $this->assertFalse(Cache::has(CacheHelper::GALLERY_CAROUSEL_KEY));
    }
}
