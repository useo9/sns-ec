<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('user')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        $product->load(['user', 'tags', 'productImages', 'orderItems.order.user']);

        return view('admin.products.show', compact('product'));
    }

    public function suspend(Product $product): RedirectResponse
    {
        $newStatus = $product->status === 0 ? 1 : 0;
        $product->update(['status' => $newStatus]);

        $msg = $newStatus === 0 ? '商品を非公開にしました。' : '商品を公開に戻しました。';

        return back()->with('success', $msg);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', '商品を削除しました。');
    }
}
