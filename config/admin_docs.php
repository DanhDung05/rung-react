<?php

/**
 * Manifest tài liệu hiển thị trong Admin → Hướng dẫn sử dụng.
 * Trang Trợ giúp đọc trực tiếp các file Markdown trong docs/ (nguồn chính).
 * Chỉ các file trong danh sách này được phép đọc.
 */
return [
    'admin-guide' => [
        'file'        => 'docs/ADMIN_GUIDE.md',
        'title'       => 'Hướng dẫn quản trị',
        'group'       => 'Bắt đầu',
        'description' => 'Từng bước sử dụng mọi chức năng Admin.',
    ],
    'admin-features' => [
        'file'        => 'docs/ADMIN_FEATURES.md',
        'title'       => 'Danh mục tính năng',
        'group'       => 'Bắt đầu',
        'description' => 'Danh sách module, route và trạng thái.',
    ],
    'releases' => [
        'file'        => 'docs/RELEASES.md',
        'title'       => 'Phát hành & version',
        'group'       => 'Vận hành',
        'description' => 'Quy trình phát hành, đồng bộ VERSION/CHANGELOG.',
    ],
    'design-tokens' => [
        'file'        => 'docs/DESIGN_TOKENS.md',
        'title'       => 'Màu & Typography',
        'group'       => 'Thiết kế',
        'description' => 'Palette, typography và bản đồ legacy.',
    ],
    'project-status' => [
        'file'        => 'docs/PROJECT_STATUS.md',
        'title'       => 'Trạng thái dự án',
        'group'       => 'Vận hành',
        'description' => 'Việc đã xong, đang làm và tồn đọng.',
    ],
    'documentation-rules' => [
        'file'        => 'docs/development/DOCUMENTATION_RULES.md',
        'title'       => 'Quy tắc tài liệu',
        'group'       => 'Kỹ thuật',
        'description' => 'Bắt buộc cập nhật tài liệu mỗi lần thay đổi.',
    ],    'architecture' => [
        'file'        => 'docs/ARCHITECTURE.md',
        'title'       => 'Kiến trúc hệ thống',
        'group'       => 'Kỹ thuật',
        'description' => 'Module, luồng dữ liệu, media, trợ giúp.',
    ],
];
