<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(): View
    {
        $carts = $this->cartService->getItemsWithProducts(auth()->user());
        $total = $this->cartService->totalPrice($carts);

        return view('carts.index', compact('carts', 'total'));
    }

    public function store(Product $product): RedirectResponse
    {
        try {
            $this->cartService->add(auth()->user(), $product);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('carts.index')->with('success', 'カートに追加しました。');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cartService->remove(auth()->user(), $product);

        return back()->with('success', 'カートから削除しました。');
    }
}
