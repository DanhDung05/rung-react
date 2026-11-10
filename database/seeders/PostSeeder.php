<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy user admin
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->error('Không tìm thấy admin user. Chạy UserSeeder trước.');
            return;
        }

        // Lấy categories
        $catCamNang     = Category::where('name', '[SAMPLE] Cẩm nang')->first();
        $catKinhNghiem  = Category::where('name', '[SAMPLE] Kinh nghiệm')->first();
        $catDiemDen     = Category::where('name', '[SAMPLE] Điểm đến')->first();
        $catTinTuc      = Category::where('name', '[SAMPLE] Tin tức')->first();

        // Lấy tags
        $tags = Tag::pluck('id', 'slug')->toArray();

        $posts = [
            [
                'title'        => '[SAMPLE] 10 điều cần biết trước khi trekking Sapa',
                'category_id'  => $catKinhNghiem?->id,
                'excerpt'      => 'Những lưu ý quan trọng giúp chuyến trekking Sapa của bạn an toàn và trọn vẹn.',
                'tags'         => ['sample-trekking', 'sample-sapa', 'sample-kinh-nghiem'],
                'is_featured'  => true,
            ],
            [
                'title'        => '[SAMPLE] Kinh nghiệm chinh phục Fansipan lần đầu',
                'category_id'  => $catKinhNghiem?->id,
                'excerpt'      => 'Hướng dẫn chi tiết cho người lần đầu chinh phục đỉnh Fansipan.',
                'tags'         => ['sample-trekking', 'sample-kinh-nghiem'],
                'is_featured'  => true,
            ],
            [
                'title'        => '[SAMPLE] Hà Giang — mùa hoa tam giác mạch',
                'category_id'  => $catDiemDen?->id,
                'excerpt'      => 'Khám phá Hà Giang mùa hoa tam giác mạch đẹp như tranh.',
                'tags'         => ['sample-ha-giang', 'sample-nhiep-anh'],
                'is_featured'  => true,
            ],
            [
                'title'        => '[SAMPLE] Top 5 địa điểm cắm trại gần Hà Nội',
                'category_id'  => $catCamNang?->id,
                'excerpt'      => 'Những địa điểm cắm trại lý tưởng cho cuối tuần.',
                'tags'         => ['sample-cam-trai', 'sample-cam-nang'],
                'is_featured'  => false,
            ],
            [
                'title'        => '[SAMPLE] Ẩm thực đường phố Đà Lạt',
                'category_id'  => $catCamNang?->id,
                'excerpt'      => 'Khám phá ẩm thực đường phố Đà Lạt — ngon, rẻ, đặc sắc.',
                'tags'         => ['sample-da-lat', 'sample-am-thuc'],
                'is_featured'  => false,
            ],
            [
                'title'        => '[SAMPLE] Văn hóa dân tộc vùng cao phía Bắc',
                'category_id'  => $catCamNang?->id,
                'excerpt'      => 'Tìm hiểu văn hóa các dân tộc thiểu số vùng cao phía Bắc.',
                'tags'         => ['sample-van-hoa', 'sample-cam-nang'],
                'is_featured'  => false,
            ],
            [
                'title'        => '[SAMPLE] Nhiếp ảnh phong cảnh — bí quyết chụp đẹp',
                'category_id'  => $catCamNang?->id,
                'excerpt'      => 'Chia sẻ bí quyết chụp ảnh phong cảnh đẹp cho người mới.',
                'tags'         => ['sample-nhiep-anh', 'sample-cam-nang'],
                'is_featured'  => false,
            ],
            [
                'title'        => '[SAMPLE] Tin tức: Rung Adventure ra mắt website mới',
                'category_id'  => $catTinTuc?->id,
                'excerpt'      => 'Chúng tôi vừa ra mắt website mới với nhiều tính năng hiện đại.',
                'tags'         => [],
                'is_featured'  => false,
            ],
        ];

        $totalTagged = 0;

        foreach ($posts as $postData) {
            $postTags = $postData['tags'];
            unset($postData['tags']);

            $slug = Str::slug($postData['title']);

            $post = Post::firstOrCreate(
                ['slug' => $slug],
                array_merge($postData, [
                    'user_id'      => $admin->id,
                    'content'      => 'Nội dung chi tiết của bài viết mẫu. Đây là dữ liệu demo.',
                    'meta_title'   => $postData['title'],
                    'status'       => 'published',
                    'published_at' => now(),
                    'view_count'   => 0,
                ])
            );

            // Gắn tags
            $tagIds = [];
            foreach ($postTags as $tagSlug) {
                if (isset($tags[$tagSlug])) {
                    $tagIds[] = $tags[$tagSlug];
                }
            }

            if (!empty($tagIds)) {
                $post->tags()->sync($tagIds);
                $totalTagged += count($tagIds);
            }
        }

        $this->command->info('✓ Đã seed posts: ' . count($posts));
        $this->command->line('   - Tags gắn: ' . $totalTagged);
    }
}