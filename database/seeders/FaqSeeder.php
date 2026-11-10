<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'category' => 'Đặt tour',
                'question' => '[SAMPLE] Làm sao để đặt tour?',
                'answer'   => 'Đây là câu trả lời mẫu. Quý khách vui lòng liên hệ hotline để được tư vấn.',
                'sort_order' => 1,
            ],
            [
                'category' => 'Đặt tour',
                'question' => '[SAMPLE] Tôi có thể hủy tour không?',
                'answer'   => 'Câu trả lời mẫu về chính sách hủy tour.',
                'sort_order' => 2,
            ],
            [
                'category' => 'Thanh toán',
                'question' => '[SAMPLE] Các hình thức thanh toán?',
                'answer'   => 'Chúng tôi chấp nhận thanh toán qua chuyển khoản và tiền mặt.',
                'sort_order' => 3,
            ],
            [
                'category' => 'Thanh toán',
                'question' => '[SAMPLE] Khi nào cần thanh toán?',
                'answer'   => 'Quý khách thanh toán trước 3 ngày khởi hành.',
                'sort_order' => 4,
            ],
            [
                'category' => 'Hành trình',
                'question' => '[SAMPLE] Tour có bao gồm ăn uống không?',
                'answer'   => 'Tùy tour, quý khách xem chi tiết trong phần lịch trình.',
                'sort_order' => 5,
            ],
            [
                'category' => 'Hành trình',
                'question' => '[SAMPLE] Cần mang theo gì khi đi tour?',
                'answer'   => 'Quý khách nên mang giày thể thao, áo khoác, và các vật dụng cá nhân.',
                'sort_order' => 6,
            ],
            [
                'category' => 'Hành trình',
                'question' => '[SAMPLE] Có hướng dẫn viên không?',
                'answer'   => 'Tất cả tour đều có hướng dẫn viên chuyên nghiệp.',
                'sort_order' => 7,
            ],
            [
                'category' => 'Khác',
                'question' => '[SAMPLE] Làm sao để liên hệ với chúng tôi?',
                'answer'   => 'Quý khách có thể gọi hotline hoặc gửi email qua trang Liên hệ.',
                'sort_order' => 8,
            ],
            [
                'category' => 'Khác',
                'question' => '[SAMPLE] Có tổ chức tour riêng không?',
                'answer'   => 'Có, chúng tôi nhận tổ chức tour riêng theo yêu cầu.',
                'sort_order' => 9,
            ],
            [
                'category' => 'Khác',
                'question' => '[SAMPLE] Có ưu đãi cho nhóm không?',
                'answer'   => 'Có, quý khách vui lòng liên hệ để được báo giá tốt nhất.',
                'sort_order' => 10,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['is_active' => true])
            );
        }

        $this->command->info('✓ Đã seed faqs: ' . count($faqs));
    }
}