<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
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

    public function create(): View
    {
        return view('products.create');
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = $this->productService->createProduct(
            auth()->user(),
            $request->validated(),
            $request->file('images') ?? [],
        );

        return redirect()->route('products.show', $product)->with('success', '商品を出品しました。');
    }

    public function edit(Product $product): View
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $product->load('productImages', 'tags');

        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $this->productService->updateProduct(
            $product,
            $request->validated(),
            $request->file('images') ?? [],
            $request->input('delete_image_ids', []),
        );

        return redirect()->route('products.show', $product)->with('success', '商品を更新しました。');
    }

    public function destroy(Product $product): RedirectResponse
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $this->productService->deleteProduct($product);

        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }
}
