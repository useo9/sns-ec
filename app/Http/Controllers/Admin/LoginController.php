<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        if (auth()->attempt($credentials) && auth()->user()->is_admin) {
            $request->session()->regenerate();
            return redirect()->route('admin.orders.index');
        }

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
