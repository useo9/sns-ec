<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getPaginatedProducts(int $perPage = 20): LengthAwarePaginator
    {
        return Product::with(['user', 'tags'])
            ->where('status', 1)
            ->latest()
            ->paginate($perPage);
    }

    public function getProduct(Product $product): Product
    {
        return $product->load(['user', 'tags']);
    }
}
