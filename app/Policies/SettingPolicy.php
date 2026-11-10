<?php

namespace App\Policies;

use App\Models\User;

/**
 * Chính sách phân quyền cho module Cài đặt hệ thống.
 *
 * Quy tắc: CHỈ role = admin được xem và thay đổi cấu hình website.
 * Editor bị chặn ở tầng controller (HTTP 403), không chỉ ẩn menu.
 */
class SettingPolicy
{
    /**
     * Xem danh sách nhóm cấu hình.
     */
    public function viewAny(User $actor): bool
    {
        return $actor->isAdmin();
    }

    /**
     * Xem một nhóm cấu hình.
     */
    public function view(User $actor): bool
    {
        return $actor->isAdmin();
    }

    /**
     * Cập nhật cấu hình.
     */
    public function update(User $actor): bool
    {
        return $actor->isAdmin();
    }
}
