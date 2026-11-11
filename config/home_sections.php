<?php

/**
 * Cấu hình các section trang chủ có thể chỉnh trong Admin.
 * Mỗi field có `role` (A/B/C/D) để Admin hiển thị đúng vị trí trên hình minh họa.
 */
return [

    'gallery' => [
        'label'       => 'Thư viện hình ảnh',
        'description' => 'Khối ảnh collage trên trang chủ: nhãn, tiêu đề, mô tả và nút dẫn tới cẩm nang.',
        'fields'      => [

            'enabled' => [
                'label'   => 'Hiển thị section',
                'type'    => 'boolean',
                'default' => true,
                'role'    => 'section',
                'hint'    => 'Tắt để ẩn toàn bộ section khỏi trang chủ.',
            ],
            'override_enabled' => [
                'label'   => 'Ghi đè màu riêng cho section',
                'type'    => 'boolean',
                'default' => false,
                'role'    => 'section',
                'hint'    => 'Bật để dùng màu bên dưới; tắt để kế thừa Palette/Typography toàn cục.',
            ],
            'background' => [
                'label'   => 'Màu nền section',
                'type'    => 'select',
                'default' => 'cream',
                'options' => ['cream' => 'Warm Cream', 'white' => 'White'],
                'role'    => 'section',
            ],

            'label_text' => [
                'label'   => 'Nhãn (A)',
                'type'    => 'string',
                'default' => 'Thư viện',
                'role'    => 'A',
            ],
            'label_color' => [
                'label'   => 'Màu nhãn',
                'type'    => 'color',
                'default' => '#a77942',
                'role'    => 'A',
            ],
            'label_on' => [
                'label'   => 'Hiện nhãn',
                'type'    => 'boolean',
                'default' => true,
                'role'    => 'A',
            ],

            'title' => [
                'label'   => 'Tiêu đề chính (B)',
                'type'    => 'string',
                'default' => 'Những khoảnh khắc',
                'role'    => 'B',
            ],
            'accent' => [
                'label'   => 'Chữ nhấn mạnh (C)',
                'type'    => 'string',
                'default' => 'đường rừng',
                'role'    => 'C',
            ],
            'accent_color' => [
                'label'   => 'Màu chữ nhấn mạnh',
                'type'    => 'color',
                'default' => '#a77942',
                'role'    => 'C',
            ],
            'title_color' => [
                'label'   => 'Màu tiêu đề',
                'type'    => 'color',
                'default' => '#102c24',
                'role'    => 'B',
            ],

            'lead' => [
                'label'   => 'Mô tả ngắn',
                'type'    => 'string',
                'default' => '',
                'role'    => 'B',
                'hint'    => 'Để trống sẽ không hiển thị mô tả.',
            ],
            'lead_color' => [
                'label'   => 'Màu mô tả',
                'type'    => 'color',
                'default' => '#52625a',
                'role'    => 'B',
            ],

            'button_text' => [
                'label'   => 'Nút — nội dung (D)',
                'type'    => 'string',
                'default' => 'Đọc cẩm nang',
                'role'    => 'D',
            ],
            'button_url' => [
                'label'   => 'Nút — liên kết',
                'type'    => 'string',
                'default' => '/bai-viet',
                'role'    => 'D',
                'hint'    => 'Đường dẫn nội bộ, ví dụ /bai-viet',
            ],
            'button_color' => [
                'label'   => 'Nút — màu chữ',
                'type'    => 'color',
                'default' => '#102c24',
                'role'    => 'D',
            ],
            'button_bg' => [
                'label'   => 'Nút — màu nền',
                'type'    => 'color',
                'default' => '#ffffff',
                'role'    => 'D',
            ],
            'button_on' => [
                'label'   => 'Hiện nút',
                'type'    => 'boolean',
                'default' => true,
                'role'    => 'D',
            ],
        ],
    ],
];
