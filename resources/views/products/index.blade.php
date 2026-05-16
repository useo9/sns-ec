<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">商品一覧</h2>
            <a href="{{ route('products.create') }}"
               class="bg-[#0095f6] hover:bg-[#1aa3ff] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                出品する
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-900/30 border border-green-700 text-green-400 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse ($products as $product)
                    @php $thumb = $product->productImages->first()?->image_path ?? $product->image_path; @endphp
                    <a href="{{ route('products.show', $product) }}"
                       class="bg-[#111111] rounded-lg overflow-hidden border border-gray-800 hover:border-gray-600 transition-colors">
                        @if ($thumb)
                            <img src="{{ image_url($thumb) }}"
                                 alt="{{ $product->title }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-800 flex items-center justify-center text-gray-500 text-xs">
                                No Image
                            </div>
                        @endif
                        <div class="p-3">
                            <p class="text-sm font-medium text-white truncate">{{ $product->title }}</p>
                            @if ($product->brand)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $product->brand }}</p>
                            @endif
                            <p class="text-base font-bold text-white mt-1">¥{{ number_format($product->price) }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $product->conditionLabel() }}</p>
                            @if ($product->productImages->count() > 1)
                                <p class="text-xs text-gray-600 mt-0.5">写真 {{ $product->productImages->count() }}枚</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 text-center text-gray-600 py-16">
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
