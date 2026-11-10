<?php

namespace App\Http\Controllers\Auth;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Hiển thị trang đăng nhập.
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Xử lý đăng nhập.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        // Thử đăng nhập
        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không đúng.',
                ]);
        }

        // Kiểm tra is_active
        if (!auth()->user()->is_active) {
            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.',
                ]);
        }

        // Regenerate session (chống session fixation)
        $request->session()->regenerate();

        // Redirect theo role
        return redirect()->intended($this->redirectTo());
    }

    /**
     * Đăng xuất.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Đã đăng xuất thành công.');
    }

    /**
     * Xác định URL redirect sau khi đăng nhập — theo role.
     */
    protected function redirectTo(): string
    {
        $role = auth()->user()->role;

        return match ($role) {
            'admin', 'editor' => route('admin.dashboard'),
            // 'user'         => route('user.dashboard'),  // Mở rộng sau
            default           => url('/'),
        };
    }
}