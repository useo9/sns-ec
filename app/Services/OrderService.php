<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private CartService $cartService) {}

    public function createOrder(User $user, array $data): Order
    {
        $carts = $this->cartService->getItemsWithProducts($user);

        if ($carts->isEmpty()) {
            throw new \DomainException('カートが空です。');
        }

        foreach ($carts as $cart) {
            if (! $cart->product->isAvailable()) {
                throw new \DomainException("「{$cart->product->title}」は既に売り切れです。");
            }
        }

        return DB::transaction(function () use ($user, $carts, $data) {
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $this->cartService->totalPrice($carts),
                'status' => 0,
                ...$data,
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'price' => $cart->product->price,
                ]);

                $cart->product->update(['status' => 2]); // 売り切れ
            }

            Cart::where('user_id', $user->id)->delete();

            return $order;
        });
    }
}
