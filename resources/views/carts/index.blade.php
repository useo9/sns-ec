<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">カート</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-900/30 border border-green-700 text-green-400 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-900/30 border border-red-700 text-red-400 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($carts->isEmpty())
                <div class="bg-[#111111] border border-gray-800 shadow-sm rounded-lg p-12 text-center text-gray-600">
                    カートに商品がありません
                </div>
            @else
                <div class="bg-[#111111] border border-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <ul class="divide-y divide-gray-800">
                        @foreach ($carts as $cart)
                            <li class="flex items-center gap-4 p-4">
                                @if ($cart->product->image_path)
                                    <img src="{{ image_url($cart->product->image_path) }}"
                                         alt="{{ $cart->product->title }}"
                                         class="w-20 h-20 object-cover rounded shrink-0">
                                @else
                                    <div class="w-20 h-20 bg-gray-800 rounded flex items-center justify-center text-gray-500 text-xs shrink-0">
                                        No Image
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('products.show', $cart->product) }}"
                                       class="text-sm font-medium text-white hover:underline truncate block">
                                        {{ $cart->product->title }}
                                    </a>
                                    @if ($cart->product->brand)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $cart->product->brand }}</p>
                                    @endif
                                    <p class="text-base font-bold text-white mt-1">
                                        ¥{{ number_format($cart->product->price) }}
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('carts.destroy', $cart->product) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-gray-600 hover:text-red-400 transition-colors">
                                        削除
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>

                    <div class="p-4 bg-black border-t border-gray-800 flex items-center justify-between">
                        <div>
                            <span class="text-sm text-gray-400">合計（{{ $carts->count() }}点）</span>
                            <span class="ml-3 text-xl font-bold text-white">¥{{ number_format($total) }}</span>
                        </div>
                        <a href="{{ route('orders.confirm') }}"
                           class="bg-[#0095f6] hover:bg-[#1aa3ff] text-white font-bold py-2 px-6 rounded-lg transition-colors text-sm">
                            購入手続きへ
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
