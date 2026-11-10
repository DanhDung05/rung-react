<?php

namespace App\Policies;

use App\Models\User;

/**
 * Chính sách phân quyền cho module Người dùng.
 *
 * Quy tắc cốt lõi:
 *  - Chỉ tài khoản role = admin mới được xem danh sách, tạo, sửa, kích hoạt tài khoản.
 *  - Editor KHÔNG được quản lý người dùng (kể cả chính mình) → chặn leo thang đặc quyền.
 *  - Không ai được tự đổi vai trò hoặc tự vô hiệu hóa chính mình (xử lý thêm ở controller).
 */
class UserPolicy
{
    /**
     * Xem danh sách người dùng.
     */
    public function viewAny(User $actor): bool
    {
        return $actor->isAdmin();
    }

    /**
     * Xem chi tiết một người dùng.
     */
    public function view(User $actor, User $target): bool
    {
        return $actor->isAdmin() || $actor->id === $target->id;
    }

    /**
     * Tạo người dùng mới.
     */
    public function create(User $actor): bool
    {
        return $actor->isAdmin();
    }

    /**
     * Cập nhật người dùng.
     */
    public function update(User $actor, User $target): bool
    {
        return $actor->isAdmin();
    }

    /**
     * Xóa người dùng — dự án không có route xóa, chỉ vô hiệu hóa.
     */
    public function delete(User $actor, User $target): bool
    {
        return false;
    }

    /**
     * Bật/tắt trạng thái hoạt động.
     */
    public function toggleActive(User $actor, User $target): bool
    {
        return $actor->isAdmin() && $actor->id !== $target->id;
    }
}
