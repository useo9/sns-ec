<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::withCount(['orders', 'products', 'posts'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filter === 'suspended') {
            $query->whereNotNull('suspended_at');
        } elseif ($request->filter === 'admin') {
            $query->where('is_admin', true);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->loadCount(['orders', 'products', 'posts', 'followers', 'following']);
        $recentPosts    = $user->posts()->latest()->limit(5)->get();
        $recentProducts = $user->products()->latest()->limit(5)->get();
        $recentOrders   = $user->orders()->latest()->limit(5)->get();

        return view('admin.users.show', compact('user', 'recentPosts', 'recentProducts', 'recentOrders'));
    }

    public function suspend(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return back()->with('error', '管理者アカウントは停止できません。');
        }

        $user->update([
            'suspended_at' => $user->isSuspended() ? null : now(),
        ]);

        $msg = $user->isSuspended() ? 'アカウントを停止しました。' : 'アカウントの停止を解除しました。';

        return back()->with('success', $msg);
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return back()->with('error', '管理者アカウントは削除できません。');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'ユーザーを削除しました。');
    }
}
