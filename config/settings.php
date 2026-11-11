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
                'contact_hours' => [
                    'label'   => 'Giờ làm việc',
                    'type'    => 'string',
                    'default' => 'Thứ 2 – Thứ 7, 08:00 – 18:00',
                    'hint'    => 'Hiển thị ở footer và trang liên hệ.',
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
        // APPEARANCE (màu nền public)
        // ============================================================
        // ============================================================
        // PALETTE (màu toàn cục)
        // ============================================================
        'palette' => [
            'label'       => 'Bảng màu',
            'description' => 'Màu nền và màu chữ dùng chung toàn website',
            'icon'        => 'palette',
            'fields'      => [
                'palette_forest' => ['label' => 'Forest Green', 'type' => 'color', 'default' => '#102c24'],
                'palette_cream'  => ['label' => 'Warm Cream', 'type' => 'color', 'default' => '#f7f3e9'],
                'palette_white'  => ['label' => 'White', 'type' => 'color', 'default' => '#ffffff'],
                'palette_earth'  => ['label' => 'Earth Brown', 'type' => 'color', 'default' => '#a77942'],
                'palette_text'   => ['label' => 'Màu chữ chính', 'type' => 'color', 'default' => '#1b2a22'],
                'palette_muted'  => ['label' => 'Màu chữ phụ', 'type' => 'color', 'default' => '#6b7a70'],
            ],
        ],

        // ============================================================
        // TYPOGRAPHY (toàn cục)
        // ============================================================
        'typography' => [
            'label'       => 'Typography',
            'description' => 'Font, màu, cỡ chữ dùng chung cho toàn website public',
            'icon'        => 'type',
            'fields'      => [
                'typo_h1_font' => ['label' => 'H1 — font', 'type' => 'string', 'default' => ''],
                'typo_h1_color' => ['label' => 'H1 — màu', 'type' => 'color', 'default' => ''],
                'typo_h1_weight' => ['label' => 'H1 — weight', 'type' => 'string', 'default' => ''],
                'typo_h1_size' => ['label' => 'H1 — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_h1_line_height' => ['label' => 'H1 — line-height', 'type' => 'string', 'default' => ''],

                'typo_h2_font' => ['label' => 'H2 — font', 'type' => 'string', 'default' => ''],
                'typo_h2_color' => ['label' => 'H2 — màu', 'type' => 'color', 'default' => ''],
                'typo_h2_weight' => ['label' => 'H2 — weight', 'type' => 'string', 'default' => ''],
                'typo_h2_size' => ['label' => 'H2 — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_h2_line_height' => ['label' => 'H2 — line-height', 'type' => 'string', 'default' => ''],

                'typo_h3_font' => ['label' => 'H3 — font', 'type' => 'string', 'default' => ''],
                'typo_h3_color' => ['label' => 'H3 — màu', 'type' => 'color', 'default' => ''],
                'typo_h3_weight' => ['label' => 'H3 — weight', 'type' => 'string', 'default' => ''],
                'typo_h3_size' => ['label' => 'H3 — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_h3_line_height' => ['label' => 'H3 — line-height', 'type' => 'string', 'default' => ''],

                'typo_page_title_font' => ['label' => 'Page Title — font', 'type' => 'string', 'default' => ''],
                'typo_page_title_color' => ['label' => 'Page Title — màu', 'type' => 'color', 'default' => ''],
                'typo_page_title_weight' => ['label' => 'Page Title — weight', 'type' => 'string', 'default' => ''],
                'typo_page_title_size' => ['label' => 'Page Title — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_page_title_line_height' => ['label' => 'Page Title — line-height', 'type' => 'string', 'default' => ''],

                'typo_section_title_font' => ['label' => 'Section Title — font', 'type' => 'string', 'default' => ''],
                'typo_section_title_color' => ['label' => 'Section Title — màu', 'type' => 'color', 'default' => ''],
                'typo_section_title_weight' => ['label' => 'Section Title — weight', 'type' => 'string', 'default' => ''],
                'typo_section_title_size' => ['label' => 'Section Title — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_section_title_line_height' => ['label' => 'Section Title — line-height', 'type' => 'string', 'default' => ''],

                'typo_body_font' => ['label' => 'Body — font', 'type' => 'string', 'default' => ''],
                'typo_body_color' => ['label' => 'Body — màu', 'type' => 'color', 'default' => ''],
                'typo_body_weight' => ['label' => 'Body — weight', 'type' => 'string', 'default' => ''],
                'typo_body_size' => ['label' => 'Body — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_body_line_height' => ['label' => 'Body — line-height', 'type' => 'string', 'default' => ''],

                'typo_lead_font' => ['label' => 'Lead — font', 'type' => 'string', 'default' => ''],
                'typo_lead_color' => ['label' => 'Lead — màu', 'type' => 'color', 'default' => ''],
                'typo_lead_weight' => ['label' => 'Lead — weight', 'type' => 'string', 'default' => ''],
                'typo_lead_size' => ['label' => 'Lead — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_lead_line_height' => ['label' => 'Lead — line-height', 'type' => 'string', 'default' => ''],

                'typo_eyebrow_font' => ['label' => 'Eyebrow — font', 'type' => 'string', 'default' => ''],
                'typo_eyebrow_color' => ['label' => 'Eyebrow — màu', 'type' => 'color', 'default' => ''],
                'typo_eyebrow_weight' => ['label' => 'Eyebrow — weight', 'type' => 'string', 'default' => ''],
                'typo_eyebrow_size' => ['label' => 'Eyebrow — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_eyebrow_line_height' => ['label' => 'Eyebrow — line-height', 'type' => 'string', 'default' => ''],

                'typo_breadcrumb_font' => ['label' => 'Breadcrumb — font', 'type' => 'string', 'default' => ''],
                'typo_breadcrumb_color' => ['label' => 'Breadcrumb — màu', 'type' => 'color', 'default' => ''],
                'typo_breadcrumb_weight' => ['label' => 'Breadcrumb — weight', 'type' => 'string', 'default' => ''],
                'typo_breadcrumb_size' => ['label' => 'Breadcrumb — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_breadcrumb_line_height' => ['label' => 'Breadcrumb — line-height', 'type' => 'string', 'default' => ''],

                'typo_footer_font' => ['label' => 'Footer — font', 'type' => 'string', 'default' => ''],
                'typo_footer_color' => ['label' => 'Footer — màu chữ', 'type' => 'color', 'default' => '#e9e4d6'],
                'typo_footer_weight' => ['label' => 'Footer — weight', 'type' => 'string', 'default' => ''],
                'typo_footer_size' => ['label' => 'Footer — cỡ chữ', 'type' => 'string', 'default' => ''],
                'typo_footer_line_height' => ['label' => 'Footer — line-height', 'type' => 'string', 'default' => ''],
            ],
        ],
        'appearance' => [
            'label'       => 'Màu nền',
            'description' => 'Hai màu nền xen kẽ cho các section trang chủ',
            'icon'        => 'palette',
            'fields'      => [
                'bg_primary' => [
                    'label'   => 'Màu nền chính',
                    'type'    => 'color',
                    'default' => '#F7F5EF',
                    'hint'    => 'Màu nền kem chủ đạo (mặc định #F7F5EF)',
                ],
                'bg_alternate' => [
                    'label'   => 'Màu nền xen kẽ',
                    'type'    => 'color',
                    'default' => '#FFFFFF',
                    'hint'    => 'Màu nền trắng xen kẽ (mặc định #FFFFFF)',
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