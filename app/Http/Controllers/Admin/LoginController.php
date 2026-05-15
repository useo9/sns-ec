<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.orders.index');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'admin-login:' . Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 6)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "ログイン試行が多すぎます。{$seconds}秒後に再試行してください。",
            ])->onlyInput('email');
        }

        if (auth()->attempt($credentials) && auth()->user()->is_admin) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            return redirect()->route('admin.orders.index');
        }

        RateLimiter::hit($key, 60);

        // 管理者でない場合は即ログアウト
        auth()->logout();

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくないか、管理者権限がありません。',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
