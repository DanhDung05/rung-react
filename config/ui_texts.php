<?php

/**
 * Nội dung hiển thị (text/label/placeholder) của các trang public.
 * Mỗi page → sections → fields. Chỉ text, không cấu trúc/CSS.
 */
return [

    'tour.index' => [
        'label'    => 'Danh sách tour',
        'sections' => [

            'hero' => [
                'label'  => 'Tiêu đề trang',
                'fields' => [
                    'eyebrow' => ['label' => 'Nhãn nhỏ', 'default' => 'Hành trình'],
                    'title'   => ['label' => 'Tiêu đề chính', 'default' => 'Chọn cung đường của bạn'],
                    'lead'    => ['label' => 'Mô tả', 'default' => 'Từ trekking nhiều ngày đến những chuyến đi trong ngày — lọc theo nhóm, độ khó và ngân sách.'],
                ],
            ],

            'filters' => [
                'label'  => 'Bộ lọc',
                'fields' => [
                    'search_label'       => ['label' => 'Nhãn ô tìm kiếm', 'default' => 'Tìm kiếm'],
                    'search_placeholder' => ['label' => 'Placeholder tìm kiếm', 'default' => 'Tên hành trình...'],
                    'category_label'     => ['label' => 'Nhãn nhóm hành trình', 'default' => 'Nhóm hành trình'],
                    'category_all'       => ['label' => 'Lựa chọn "tất cả" (nhóm)', 'default' => 'Tất cả'],
                    'difficulty_label'   => ['label' => 'Nhãn độ khó', 'default' => 'Độ khó'],
                    'difficulty_all'     => ['label' => 'Lựa chọn "tất cả" (độ khó)', 'default' => 'Tất cả'],
                    'sort_label'         => ['label' => 'Nhãn sắp xếp', 'default' => 'Sắp xếp'],
                    'sort_newest'        => ['label' => 'Sắp xếp — mới nhất', 'default' => 'Mới nhất'],
                    'sort_price_asc'     => ['label' => 'Sắp xếp — giá tăng', 'default' => 'Giá thấp → cao'],
                    'sort_price_desc'    => ['label' => 'Sắp xếp — giá giảm', 'default' => 'Giá cao → thấp'],
                    'sort_popular'       => ['label' => 'Sắp xếp — xem nhiều', 'default' => 'Xem nhiều'],
                    'reset'              => ['label' => 'Nút xóa bộ lọc', 'default' => 'Xóa bộ lọc'],
                ],
            ],

            'results' => [
                'label'  => 'Kết quả & trạng thái trống',
                'fields' => [
                    'count_suffix'   => ['label' => 'Đơn vị đếm', 'default' => 'hành trình'],
                    'empty_title'    => ['label' => 'Tiêu đề khi không có kết quả', 'default' => 'Không tìm thấy hành trình phù hợp'],
                    'empty_text'     => ['label' => 'Mô tả khi không có kết quả', 'default' => 'Thử bỏ bớt bộ lọc hoặc tìm với từ khóa khác.'],
                ],
            ],

            'cta' => [
                'label'  => 'Khối CTA cuối trang',
                'fields' => [
                    'eyebrow' => ['label' => 'Nhãn nhỏ', 'default' => 'Chưa chắc chắn?'],
                    'title'   => ['label' => 'Tiêu đề', 'default' => 'Để chúng tôi thiết kế hành trình riêng cho bạn'],
                    'button'  => ['label' => 'Chữ trên nút', 'default' => 'Liên hệ tư vấn'],
                ],
            ],
        ],
    ],
];
