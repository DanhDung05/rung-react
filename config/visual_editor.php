<?php

/**
 * Registry các phần tử có thể chỉnh trong Visual Editor.
 * Mỗi field: id (page.section.field), label, type (text|textarea|button|section), default.
 */
return [

    'home' => [
        'label'    => 'Trang chủ',
        'sections' => [

            'welcome' => [
                'label'  => 'Welcome',
                'fields' => [
                    'enabled' => ['label' => 'Bật section này', 'type' => 'section', 'default' => true],
                    'eyebrow' => ['label' => 'Nhãn nhỏ', 'type' => 'text', 'default' => 'Rừng Adventure · Việt Nam'],
                    'title'   => ['label' => 'Tiêu đề', 'type' => 'text', 'default' => 'Chào mừng đến'],
                    'accent'  => ['label' => 'Chữ nhấn mạnh', 'type' => 'text', 'default' => 'Rừng Adventure'],
                    'lead'    => ['label' => 'Mô tả', 'type' => 'textarea', 'default' => ''],
                ],
            ],

            'tours' => [
                'label'  => 'Tour nổi bật',
                'fields' => [
                    'enabled' => ['label' => 'Bật section này', 'type' => 'section', 'default' => true],
                    'eyebrow' => ['label' => 'Nhãn nhỏ', 'type' => 'text', 'default' => 'Chọn hành trình của bạn'],
                    'title'   => ['label' => 'Tiêu đề', 'type' => 'text', 'default' => 'Hành trình'],
                    'accent'  => ['label' => 'Chữ nhấn mạnh', 'type' => 'text', 'default' => 'nổi bật'],
                    'button'  => ['label' => 'Nút xem tất cả', 'type' => 'button', 'default' => 'Xem tất cả hành trình', 'url' => '/tour'],
                ],
            ],

            'band' => [
                'label'  => 'Banner thiên nhiên',
                'fields' => [
                    'enabled' => ['label' => 'Bật section này', 'type' => 'section', 'default' => true],
                    'image'   => ['label' => 'Ảnh nền (URL/Media)', 'type' => 'image', 'default' => ''],
                    'eyebrow' => ['label' => 'Nhãn nhỏ', 'type' => 'text', 'default' => 'Triết lý hành trình'],
                    'quote'   => ['label' => 'Câu trích dẫn', 'type' => 'textarea', 'default' => '“Chúng tôi không đưa bạn đi thật nhanh. Chúng tôi đưa bạn đi thật sâu.”'],
                    'source'  => ['label' => 'Nguồn', 'type' => 'text', 'default' => 'Rừng Adventure'],
                ],
            ],

            'gallery' => [
                'label'  => 'Thư viện',
                'fields' => [
                    'enabled' => ['label' => 'Bật section này', 'type' => 'section', 'default' => true],
                    'label'   => ['label' => 'Nhãn nhỏ', 'type' => 'text', 'default' => 'Thư viện'],
                    'title'   => ['label' => 'Tiêu đề', 'type' => 'text', 'default' => 'Những khoảnh khắc'],
                    'accent'  => ['label' => 'Chữ nhấn mạnh', 'type' => 'text', 'default' => 'đường rừng'],
                    'button'  => ['label' => 'Nút', 'type' => 'button', 'default' => 'Đọc cẩm nang', 'url' => '/bai-viet'],
                ],
            ],

            'posts' => [
                'label'  => 'Cẩm nang',
                'fields' => [
                    'enabled' => ['label' => 'Bật section này', 'type' => 'section', 'default' => true],
                    'eyebrow' => ['label' => 'Nhãn nhỏ', 'type' => 'text', 'default' => 'Cẩm nang'],
                    'title'   => ['label' => 'Tiêu đề', 'type' => 'text', 'default' => 'Ghi chép'],
                    'accent'  => ['label' => 'Chữ nhấn mạnh', 'type' => 'text', 'default' => 'từ rừng'],
                    'button'  => ['label' => 'Nút xem tất cả', 'type' => 'button', 'default' => 'Tất cả bài viết', 'url' => '/bai-viet'],
                ],
            ],

            'cta' => [
                'label'  => 'CTA cuối trang',
                'fields' => [
                    'enabled' => ['label' => 'Bật section này', 'type' => 'section', 'default' => true],
                    'image'   => ['label' => 'Ảnh nền CTA (URL/Media)', 'type' => 'image', 'default' => ''],
                    'eyebrow' => ['label' => 'Nhãn nhỏ', 'type' => 'text', 'default' => 'Bắt đầu từ đây'],
                    'title'   => ['label' => 'Tiêu đề', 'type' => 'text', 'default' => 'Sẵn sàng cho'],
                    'accent'  => ['label' => 'Chữ nhấn mạnh', 'type' => 'text', 'default' => 'hành trình tiếp theo?'],
                    'lead'    => ['label' => 'Mô tả', 'type' => 'textarea', 'default' => ''],
                    'button'  => ['label' => 'Nút liên hệ', 'type' => 'button', 'default' => 'Liên hệ tư vấn', 'url' => '/lien-he'],
                ],
            ],
        ],
    ],
];
