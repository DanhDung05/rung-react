<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'name'    => '[SAMPLE] Nguyễn Văn A',
                'email'   => 'nguyenvana@example.com',
                'phone'   => '0901234567',
                'subject' => 'Hỏi về tour Sapa',
                'message' => 'Tôi muốn hỏi về tour Sapa 3N2Đ. Còn chỗ không? Giá bao nhiêu?',
                'status'  => 'new',
            ],
            [
                'name'    => '[SAMPLE] Trần Thị B',
                'email'   => 'tranthib@example.com',
                'phone'   => '0907654321',
                'subject' => 'Đặt tour cho nhóm 10 người',
                'message' => 'Tôi muốn đặt tour cho nhóm 10 người vào tháng 12. Có ưu đãi không?',
                'status'  => 'read',
            ],
            [
                'name'    => '[SAMPLE] Lê Văn C',
                'email'   => 'levanc@example.com',
                'phone'   => null,
                'subject' => 'Hợp tác',
                'message' => 'Tôi muốn hợp tác quảng cáo trên website. Vui lòng liên hệ lại.',
                'status'  => 'replied',
            ],
            [
                'name'    => '[SAMPLE] Phạm Thị D',
                'email'   => 'phamthid@example.com',
                'phone'   => '0912345678',
                'subject' => null,
                'message' => 'Câu hỏi mẫu không có tiêu đề.',
                'status'  => 'new',
            ],
            [
                'name'    => '[SAMPLE] Hoàng Văn E',
                'email'   => 'hoangvane@example.com',
                'phone'   => '0987654321',
                'subject' => 'Phản hồi về tour',
                'message' => 'Tôi đã đi tour Fansipan. Rất tuyệt vời, cảm ơn công ty!',
                'status'  => 'archived',
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::firstOrCreate(
                ['email' => $contact['email'], 'subject' => $contact['subject']],
                array_merge($contact, [
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Seeder',
                ])
            );
        }

        $this->command->info('✓ Đã seed contacts: ' . count($contacts));
    }
}