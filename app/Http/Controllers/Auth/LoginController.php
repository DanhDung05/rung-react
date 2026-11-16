<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LoginController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $key = Str::lower((string) $request->input('email')) . '|' . $request->ip();
        $throttleKey = 'login:' . $key;

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => "Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau {$seconds} giây.",
                ]);
        }

        $credentials = $request->only('email', 'password');
        $credentials['is_active'] = 1;
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

            // Chỉ báo "bị vô hiệu hóa" khi đúng mật khẩu; sai mật khẩu luôn dùng thông báo chung.
            $user = User::where('email', $request->input('email'))->first();

            if ($user && ! $user->is_active && Hash::check((string) $request->input('password'), $user->password)) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.',
                    ]);
            }

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không đúng.',
                ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo());
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Đã đăng xuất thành công.');
    }

    protected function redirectTo(): string
    {
        $role = auth()->user()->role;

        return match ($role) {
            'admin', 'editor' => route('admin.dashboard'),
            default           => url('/'),
        };
    }
}
