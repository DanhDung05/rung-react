<?php

namespace App\Support;

use App\Models\User;

class AdminChannel
{
    /**
     * Chỉ admin/editor đang hoạt động được nghe kênh thông báo quản trị.
     */
    public static function allows(?User $user): bool
    {
        return $user !== null
            && $user->is_active === true
            && in_array($user->role, ['admin', 'editor'], true);
    }
}
