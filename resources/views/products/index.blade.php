<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">商品一覧</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($products as $product)
                    <a href="{{ route('products.show', $product) }}"
                       class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                 alt="{{ $product->title }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                No Image
                            </div>
                        @endif
                        <div class="p-3">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $product->title }}</p>
                            @if ($product->brand)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $product->brand }}</p>
                            @endif
                            <p class="text-base font-bold text-gray-900 mt-1">¥{{ number_format($product->price) }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $product->conditionLabel() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 text-center text-gray-400 py-16">
                        出品中の商品はありません
                    </div>
                @endforelse
            </div>

            @if ($products->hasPages())
                <div class="mt-6">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
