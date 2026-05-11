<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(): View
    {
        $products = $this->productService->getPaginatedProducts();

        return view('products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        $product = $this->productService->getProduct($product);

        return view('products.show', compact('product'));
    }
}
