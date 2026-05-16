<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::has('products')
            ->withCount('products')
            ->withSum('products', 'price')
            ->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filter === 'suspended') {
            $query->whereNotNull('suspended_at');
        }

        $shops = $query->paginate(20)->withQueryString();

        return view('admin.shops.index', compact('shops'));
    }

    public function show(User $shop): View
    {
        $shop->loadCount('products');
        $products = $shop->products()->with('tags')->latest()->paginate(12);

        return view('admin.shops.show', compact('shop', 'products'));
    }

    public function suspend(User $shop): RedirectResponse
    {
        if ($shop->is_admin) {
            return back()->with('error', '管理者アカウントは操作できません。');
        }

        $shop->update([
            'suspended_at' => $shop->isSuspended() ? null : now(),
        ]);

        $msg = $shop->isSuspended() ? 'ショップを停止しました。' : 'ショップの停止を解除しました。';

        return back()->with('success', $msg);
    }

    public function destroy(User $shop): RedirectResponse
    {
        if ($shop->is_admin) {
            return back()->with('error', '管理者アカウントは削除できません。');
        }

        $shop->delete();

        return redirect()->route('admin.shops.index')->with('success', 'ショップ（ユーザー）を削除しました。');
    }
}
