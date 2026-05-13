<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getPaginatedProducts(int $perPage = 20): LengthAwarePaginator
    {
        return Product::with(['user', 'tags', 'productImages'])
            ->where('status', 1)
            ->latest()
            ->paginate($perPage);
    }

    public function getProduct(Product $product): Product
    {
        return $product->load(['user', 'tags', 'productImages']);
    }

    public function createProduct(User $user, array $data, array $images = []): Product
    {
        $product = $user->products()->create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'brand'       => $data['brand'] ?? null,
            'size'        => $data['size'] ?? null,
            'category'    => $data['category'] ?? null,
            'condition'   => $data['condition'],
            'status'      => $data['status'],
        ]);

        $this->syncTags($product, $data['tags'] ?? '');
        $this->storeImages($product, $images);

        return $product;
    }

    public function updateProduct(Product $product, array $data, array $newImages = [], array $deleteImageIds = []): Product
    {
        $product->update([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'brand'       => $data['brand'] ?? null,
            'size'        => $data['size'] ?? null,
            'category'    => $data['category'] ?? null,
            'condition'   => $data['condition'],
            'status'      => $data['status'],
        ]);

        $this->syncTags($product, $data['tags'] ?? '');
        $this->deleteImages($product, $deleteImageIds);
        $this->storeImages($product, $newImages);

        return $product;
    }

    public function deleteProduct(Product $product): void
    {
        foreach ($product->productImages as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();
    }

    private function storeImages(Product $product, array $images): void
    {
        $nextOrder = $product->productImages()->max('sort_order') + 1;

        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');

            $product->productImages()->create([
                'image_path' => $path,
                'sort_order' => $nextOrder + $index,
            ]);
        }

        $this->syncPrimaryImage($product);
    }

    private function deleteImages(Product $product, array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $images = $product->productImages()->whereIn('id', $ids)->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $this->syncPrimaryImage($product);
    }

    private function syncPrimaryImage(Product $product): void
    {
        $first = $product->productImages()->orderBy('sort_order')->first();
        $product->update(['image_path' => $first?->image_path]);
    }

    private function syncTags(Product $product, string $tagsInput): void
    {
        if (empty(trim($tagsInput))) {
            $product->tags()->detach();
            return;
        }

        $tagIds = collect(explode(',', $tagsInput))
            ->map(fn($name) => trim($name))
            ->filter()
            ->map(fn($name) => Tag::firstOrCreate(['name' => $name])->id);

        $product->tags()->sync($tagIds);
    }
}
