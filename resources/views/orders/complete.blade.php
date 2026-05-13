<x-app-layout>
    <div class="py-16">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#111111] border border-gray-800 shadow-sm rounded-lg p-8 text-center">

                {{-- 完了アイコン --}}
                <div class="w-16 h-16 bg-green-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h1 class="text-xl font-bold text-white">ご注文ありがとうございました</h1>
                <p class="text-sm text-gray-500 mt-1">注文番号：#{{ $order->id }}</p>

                {{-- 購入商品一覧 --}}
                <div class="mt-6 text-left border border-gray-800 rounded-lg overflow-hidden">
                    <ul class="divide-y divide-gray-800">
                        @foreach ($order->items as $item)
                            <li class="flex items-center gap-3 p-3">
                                @if ($item->product->image_path)
                                    <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                         alt="{{ $item->product->title }}"
                                         class="w-12 h-12 object-cover rounded shrink-0">
                                @else
                                    <div class="w-12 h-12 bg-gray-800 rounded shrink-0"></div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">{{ $item->product->title }}</p>
                                </div>
                                <p class="text-sm font-bold text-white shrink-0">¥{{ number_format($item->price) }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex justify-between items-center px-3 py-2 bg-black border-t border-gray-800">
                        <span class="text-sm text-gray-400">合計</span>
                        <span class="font-bold text-white">¥{{ number_format($order->total_price) }}</span>
                    </div>
                </div>

                {{-- 配送先情報 --}}
                <div class="mt-5 text-left text-sm text-gray-300 space-y-1 border border-gray-800 rounded-lg p-4">
                    <p class="font-semibold text-gray-200 mb-1">配送先</p>
                    <p>{{ $order->shipping_name }}</p>
                    <p>〒{{ $order->shipping_postal_code }} {{ $order->shipping_prefecture }}</p>
                    <p>{{ $order->shipping_address }}
                        @if ($order->shipping_building)
                            {{ $order->shipping_building }}
                        @endif
                    </p>
                </div>

                <div class="mt-8">
                    <a href="{{ route('products.index') }}"
                       class="text-sm text-[#0095f6] hover:underline">
                        引き続きお買い物を楽しむ →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
