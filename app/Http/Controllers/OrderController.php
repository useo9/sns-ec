<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private CartService $cartService,
    ) {}

    public function confirm(): View|RedirectResponse
    {
        $carts = $this->cartService->getItemsWithProducts(auth()->user());

        if ($carts->isEmpty()) {
            return redirect()->route('carts.index')->with('error', 'カートが空です。');
        }

        $total = $this->cartService->totalPrice($carts);

        return view('orders.confirm', compact('carts', 'total'));
    }

    public function store(OrderRequest $request): RedirectResponse
    {
        try {
            $order = $this->orderService->createOrder(auth()->user(), $request->validated());
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('orders.complete', $order);
    }

    public function complete(Order $order): View
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items.product');

        return view('orders.complete', compact('order'));
    }
}
