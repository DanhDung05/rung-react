<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Chỉ cho phép user có role admin hoặc editor.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chưa đăng nhập → về trang login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Sai role → 403
        if (!in_array(auth()->user()->role, ['admin', 'editor'])) {
            abort(403, 'Bạn không có quyền truy cập khu vực quản trị.');
        }

        // Tài khoản bị vô hiệu
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Tài khoản đã bị vô hiệu hóa.']);
        }

        return $next($request);
    }
}