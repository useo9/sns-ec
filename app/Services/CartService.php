<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class CartService
{
    public function add(User $user, Product $product): void
    {
        if (! $product->isAvailable()) {
            throw new \DomainException('この商品は現在購入できません。');
        }

        if ($product->user_id === $user->id) {
            throw new \DomainException('自分の商品はカートに追加できません。');
        }

        Cart::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function remove(User $user, Product $product): void
    {
        Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();
    }

    public function getItemsWithProducts(User $user): Collection
    {
        return Cart::with(['product.user'])
            ->where('user_id', $user->id)
            ->get();
    }

    public function totalPrice(Collection $carts): int
    {
        return $carts->sum(fn($cart) => $cart->product->price);
    }
}
