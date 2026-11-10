<?php

return [

    'groups' => [

        // ============================================================
        // GENERAL
        // ============================================================
        'general' => [
            'label'       => 'Cấu hình chung',
            'description' => 'Thông tin cơ bản của website',
            'icon'        => 'home',
            'fields'      => [
                'site_name' => [
                    'label'   => 'Tên website',
                    'type'    => 'string',
                    'default' => 'Rung Adventure',
                ],
                'site_description' => [
                    'label'   => 'Mô tả ngắn',
                    'type'    => 'text',
                    'default' => 'Khám phá những hành trình trekking tuyệt vời.',
                ],
                'site_logo' => [
                    'label' => 'Logo',
                    'type'  => 'image',
                    'hint'  => 'Kích thước khuyến nghị: 200x60px',
                ],
                'site_favicon' => [
                    'label' => 'Favicon',
                    'type'  => 'image',
                    'hint'  => 'Kích thước khuyến nghị: 32x32px',
                ],
            ],
        ],

               // ============================================================
        // CONTACT
        // ============================================================
        'contact' => [
            'label'       => 'Liên hệ',
            'description' => 'Thông tin liên hệ',
            'icon'        => 'phone',
            'fields'      => [
                'contact_email' => [
                    'label'   => 'Email hiển thị',
                    'type'    => 'string',
                    'default' => 'hello@rungadventure.com',
                    'hint'    => 'Email hiển thị cho khách (footer, trang liên hệ). Chỉ 1 email.',
                ],
                'contact_notification_emails' => [
                    'label'   => 'Email nhận thông báo',
                    'type'    => 'text',
                    'default' => 'hello@rungadventure.com',
                    'hint'    => 'Email nhận thông báo khi có liên hệ mới. Nhiều email cách nhau dấu phẩy. Ví dụ: admin1@x.com, admin2@y.com',
                ],
                'contact_phone' => [
                    'label'   => 'Số điện thoại',
                    'type'    => 'string',
                    'default' => '0123 456 789',
                ],
                'contact_address' => [
                    'label'   => 'Địa chỉ',
                    'type'    => 'string',
                    'default' => 'Hà Nội, Việt Nam',
                ],
            ],
        ],

        // ============================================================
        // SOCIAL
        // ============================================================
        'social' => [
            'label'       => 'Mạng xã hội',
            'description' => 'Liên kết mạng xã hội',
            'icon'        => 'share',
            'fields'      => [
                'social_facebook' => [
                    'label' => 'Facebook',
                    'type'  => 'string',
                ],
                'social_instagram' => [
                    'label' => 'Instagram',
                    'type'  => 'string',
                ],
                'social_youtube' => [
                    'label' => 'YouTube',
                    'type'  => 'string',
                ],
                'social_tiktok' => [
                    'label' => 'TikTok',
                    'type'  => 'string',
                ],
            ],
        ],

        // ============================================================
        // SEO
        // ============================================================
        'seo' => [
            'label'       => 'SEO',
            'description' => 'Cấu hình SEO mặc định',
            'icon'        => 'search',
            'fields'      => [
                'seo_default_title' => [
                    'label'   => 'Meta title mặc định',
                    'type'    => 'string',
                    'default' => 'Rung Adventure — Khám phá Việt Nam',
                ],
                'seo_default_description' => [
                    'label'   => 'Meta description mặc định',
                    'type'    => 'text',
                    'default' => 'Khám phá những hành trình trekking tuyệt vời cùng Rung Adventure.',
                    'hint'    => 'Khuyến nghị 150-160 ký tự',
                ],
                'seo_keywords' => [
                    'label' => 'Từ khóa SEO',
                    'type'  => 'string',
                    'hint'  => 'Phân cách bằng dấu phẩy',
                ],
                'google_analytics_id' => [
                    'label' => 'Google Analytics ID',
                    'type'  => 'string',
                    'hint'  => 'Ví dụ: G-XXXXXXXXXX',
                ],
            ],
        ],

        // ============================================================
        // THEME
        // ============================================================
        'theme' => [
            'label'       => 'Giao diện',
            'description' => 'Màu sắc và font chữ',
            'icon'        => 'palette',
            'fields'      => [
                // MÀU CHUNG
                'theme_primary_color' => [
                    'label'   => 'Màu chính',
                    'type'    => 'color',
                    'default' => '#307a55',
                    'hint'    => 'Màu thương hiệu chính (nút, link, ...)',
                ],
                'theme_secondary_color' => [
                    'label'   => 'Màu phụ',
                    'type'    => 'color',
                    'default' => '#f97316',
                    'hint'    => 'Màu nhấn (nút secondary, badge)',
                ],

                // H1
                'theme_h1_color' => [
                    'label'   => 'H1 — Màu chữ',
                    'type'    => 'color',
                    'default' => '#0f172a',
                ],
                'theme_h1_font' => [
                    'label'   => 'H1 — Font chữ',
                    'type'    => 'select',
                    'default' => 'Plus Jakarta Sans',
                    'options' => [
                        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
                        'Inter'             => 'Inter',
                        'Poppins'           => 'Poppins',
                        'Montserrat'        => 'Montserrat',
                        'Roboto'            => 'Roboto',
                        'Playfair Display'  => 'Playfair Display',
                        'Lora'              => 'Lora',
                        'Merriweather'      => 'Merriweather',
                        'Open Sans'         => 'Open Sans',
                        'Nunito'            => 'Nunito',
                        'Raleway'           => 'Raleway',
                        'Oswald'            => 'Oswald',
                    ],
                ],

                // H2
                'theme_h2_color' => [
                    'label'   => 'H2 — Màu chữ',
                    'type'    => 'color',
                    'default' => '#0f172a',
                ],
                'theme_h2_font' => [
                    'label'   => 'H2 — Font chữ',
                    'type'    => 'select',
                    'default' => 'Plus Jakarta Sans',
                    'options' => [
                        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
                        'Inter'             => 'Inter',
                        'Poppins'           => 'Poppins',
                        'Montserrat'        => 'Montserrat',
                        'Roboto'            => 'Roboto',
                        'Playfair Display'  => 'Playfair Display',
                        'Lora'              => 'Lora',
                        'Merriweather'      => 'Merriweather',
                        'Open Sans'         => 'Open Sans',
                        'Nunito'            => 'Nunito',
                        'Raleway'           => 'Raleway',
                        'Oswald'            => 'Oswald',
                    ],
                ],

                // H3
                'theme_h3_color' => [
                    'label'   => 'H3 — Màu chữ',
                    'type'    => 'color',
                    'default' => '#0f172a',
                ],
                'theme_h3_font' => [
                    'label'   => 'H3 — Font chữ',
                    'type'    => 'select',
                    'default' => 'Plus Jakarta Sans',
                    'options' => [
                        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
                        'Inter'             => 'Inter',
                        'Poppins'           => 'Poppins',
                        'Montserrat'        => 'Montserrat',
                        'Roboto'            => 'Roboto',
                        'Playfair Display'  => 'Playfair Display',
                        'Lora'              => 'Lora',
                        'Merriweather'      => 'Merriweather',
                        'Open Sans'         => 'Open Sans',
                        'Nunito'            => 'Nunito',
                        'Raleway'           => 'Raleway',
                        'Oswald'            => 'Oswald',
                    ],
                ],

                // H4
                'theme_h4_color' => [
                    'label'   => 'H4 — Màu chữ',
                    'type'    => 'color',
                    'default' => '#0f172a',
                ],
                'theme_h4_font' => [
                    'label'   => 'H4 — Font chữ',
                    'type'    => 'select',
                    'default' => 'Plus Jakarta Sans',
                    'options' => [
                        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
                        'Inter'             => 'Inter',
                        'Poppins'           => 'Poppins',
                        'Montserrat'        => 'Montserrat',
                        'Roboto'            => 'Roboto',
                        'Playfair Display'  => 'Playfair Display',
                        'Lora'              => 'Lora',
                        'Merriweather'      => 'Merriweather',
                        'Open Sans'         => 'Open Sans',
                        'Nunito'            => 'Nunito',
                        'Raleway'           => 'Raleway',
                        'Oswald'            => 'Oswald',
                    ],
                ],

                // H5
                'theme_h5_color' => [
                    'label'   => 'H5 — Màu chữ',
                    'type'    => 'color',
                    'default' => '#0f172a',
                ],
                'theme_h5_font' => [
                    'label'   => 'H5 — Font chữ',
                    'type'    => 'select',
                    'default' => 'Plus Jakarta Sans',
                    'options' => [
                        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
                        'Inter'             => 'Inter',
                        'Poppins'           => 'Poppins',
                        'Montserrat'        => 'Montserrat',
                        'Roboto'            => 'Roboto',
                        'Playfair Display'  => 'Playfair Display',
                        'Lora'              => 'Lora',
                        'Merriweather'      => 'Merriweather',
                        'Open Sans'         => 'Open Sans',
                        'Nunito'            => 'Nunito',
                        'Raleway'           => 'Raleway',
                        'Oswald'            => 'Oswald',
                    ],
                ],

                // H6
                'theme_h6_color' => [
                    'label'   => 'H6 — Màu chữ',
                    'type'    => 'color',
                    'default' => '#0f172a',
                ],
                'theme_h6_font' => [
                    'label'   => 'H6 — Font chữ',
                    'type'    => 'select',
                    'default' => 'Plus Jakarta Sans',
                    'options' => [
                        'Plus Jakarta Sans' => 'Plus Jakarta Sans',
                        'Inter'             => 'Inter',
                        'Poppins'           => 'Poppins',
                        'Montserrat'        => 'Montserrat',
                        'Roboto'            => 'Roboto',
                        'Playfair Display'  => 'Playfair Display',
                        'Lora'              => 'Lora',
                        'Merriweather'      => 'Merriweather',
                        'Open Sans'         => 'Open Sans',
                        'Nunito'            => 'Nunito',
                        'Raleway'           => 'Raleway',
                        'Oswald'            => 'Oswald',
                    ],
                ],
            ],
        ],


        // ============================================================
        'about' => [
    'label'       => 'Trang giới thiệu',
    'description' => 'Nội dung trang giới thiệu',
    'icon'        => 'document',
    'fields'      => [
          'about_page_slug' => [
            'label'   => 'Slug trang giới thiệu',
            'type'    => 'string',
            'default' => 'gioi-thieu',
            'hint'    => 'Slug của trang tĩnh dùng làm trang giới thiệu. Mặc định: gioi-thieu',
        ],
        'about_hero_title' => [
            'label'   => 'Hero — Tiêu đề',
            'type'    => 'string',
            'default' => 'Về Rừng Adventure',
        ],
        'about_hero_subtitle' => [
            'label'   => 'Hero — Phụ đề',
            'type'    => 'text',
            'default' => 'Hành trình khám phá thiên nhiên Việt Nam',
        ],

        'about_story_title' => [
            'label'   => 'Câu chuyện — Tiêu đề',
            'type'    => 'string',
            'default' => 'Câu chuyện của chúng tôi',
        ],

        'about_mission_title' => [
            'label'   => 'Sứ mệnh — Tiêu đề',
            'type'    => 'string',
            'default' => 'Sứ mệnh',
        ],
        'about_mission_content' => [
            'label'   => 'Sứ mệnh — Nội dung',
            'type'    => 'text',
            'default' => 'Mang đến những trải nghiệm du lịch bền vững, tôn trọng thiên nhiên và văn hóa địa phương.',
        ],

        'about_vision_title' => [
            'label'   => 'Tầm nhìn — Tiêu đề',
            'type'    => 'string',
            'default' => 'Tầm nhìn',
        ],
        'about_vision_content' => [
            'label'   => 'Tầm nhìn — Nội dung',
            'type'    => 'text',
            'default' => 'Trở thành đơn vị tổ chức du lịch trekking hàng đầu Việt Nam vào năm 2030.',
        ],

        'about_values_title' => [
            'label'   => 'Giá trị cốt lõi — Tiêu đề',
            'type'    => 'string',
            'default' => 'Giá trị cốt lõi',
        ],
        'about_values_content' => [
            'label'   => 'Giá trị cốt lõi — Nội dung',
            'type'    => 'text',
            'default' => 'An toàn — Bền vững — Tận tâm — Chuyên nghiệp',
            'hint'    => 'Phân cách bằng dấu gạch ngang (—). Mỗi giá trị sẽ là 1 item.',
        ],
    ],
],


//=======================================
'hero' => [
    'label'       => 'Ảnh Hero',
    'description' => 'Ảnh nền cho các trang index và trang tĩnh. Để trống sẽ dùng ảnh đầu tiên của slider.',
    'icon'        => 'photo',
    'fields'      => [
        'hero_image_blog' => [
            'label' => 'Blog — Ảnh hero',
            'type'  => 'image',
            'hint'  => 'Để trống sẽ dùng ảnh đầu tiên của slider.',
        ],
        'hero_image_tour' => [
            'label' => 'Tour — Ảnh hero',
            'type'  => 'image',
            'hint'  => 'Để trống sẽ dùng ảnh đầu tiên của slider.',
        ],
        'hero_image_destination' => [
            'label' => 'Điểm đến — Ảnh hero',
            'type'  => 'image',
            'hint'  => 'Để trống sẽ dùng ảnh đầu tiên của slider.',
        ],
        'hero_image_about' => [
            'label' => 'Giới thiệu — Ảnh hero',
            'type'  => 'image',
            'hint'  => 'Để trống sẽ dùng ảnh đầu tiên của slider.',
        ],
        'hero_image_contact' => [
            'label' => 'Liên hệ — Ảnh hero',
            'type'  => 'image',
            'hint'  => 'Để trống sẽ dùng ảnh đầu tiên của slider.',
        ],
        'hero_image_faq' => [
            'label' => 'FAQ — Ảnh hero',
            'type'  => 'image',
            'hint'  => 'Để trống sẽ dùng ảnh đầu tiên của slider.',
        ],
    ],
],
// ============================================================

    ],

];