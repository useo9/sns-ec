<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                <div class="md:flex">

                    {{-- 商品画像 --}}
                    <div class="md:w-1/2 shrink-0">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                 alt="{{ $product->title }}"
                                 class="w-full h-96 object-cover">
                        @else
                            <div class="w-full h-96 bg-gray-100 flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif
                    </div>

                    {{-- 商品情報 --}}
                    <div class="md:w-1/2 p-6 flex flex-col">
                        <h1 class="text-xl font-bold text-gray-900">{{ $product->title }}</h1>
                        <p class="text-2xl font-bold text-gray-900 mt-2">¥{{ number_format($product->price) }}</p>

                        <dl class="mt-4 space-y-2 text-sm">
                            @if ($product->brand)
                                <div class="flex">
                                    <dt class="w-24 text-gray-500 shrink-0">ブランド</dt>
                                    <dd class="text-gray-800">{{ $product->brand }}</dd>
                                </div>
                            @endif
                            @if ($product->size)
                                <div class="flex">
                                    <dt class="w-24 text-gray-500 shrink-0">サイズ</dt>
                                    <dd class="text-gray-800">{{ $product->size }}</dd>
                                </div>
                            @endif
                            @if ($product->category)
                                <div class="flex">
                                    <dt class="w-24 text-gray-500 shrink-0">カテゴリ</dt>
                                    <dd class="text-gray-800">{{ $product->category }}</dd>
                                </div>
                            @endif
                            <div class="flex">
                                <dt class="w-24 text-gray-500 shrink-0">商品の状態</dt>
                                <dd class="text-gray-800">{{ $product->conditionLabel() }}</dd>
                            </div>
                        </dl>

                        @if ($product->tags->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach ($product->tags as $tag)
                                    <span class="text-xs bg-gray-100 text-gray-600 rounded-full px-2 py-0.5">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- カート追加ボタン --}}
                        <div class="mt-auto pt-6">
                            @if ($product->isAvailable() && auth()->id() !== $product->user_id)
                                <form method="POST" action="{{ route('carts.store', $product) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                        カートに追加する
                                    </button>
                                </form>
                            @elseif (! $product->isAvailable())
                                <div class="w-full bg-gray-200 text-gray-500 font-bold py-3 px-4 rounded-lg text-center">
                                    売り切れ
                                </div>
                            @else
                                <div class="w-full bg-gray-100 text-gray-400 font-bold py-3 px-4 rounded-lg text-center">
                                    自分の出品商品です
                                </div>
                            @endif
                        </div>

                        {{-- 出品者 --}}
                        <div class="mt-4 flex items-center gap-2 border-t pt-4">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-xs font-bold text-gray-600 shrink-0">
                                {{ mb_substr($product->user->name, 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-600">{{ $product->user->name }}</span>
                        </div>
                    </div>
                </div>

                {{-- 商品説明 --}}
                @if ($product->description)
                    <div class="p-6 border-t">
                        <h2 class="font-semibold text-gray-700 mb-2">商品説明</h2>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $product->description }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:underline">← 商品一覧へ戻る</a>
            </div>
        </div>
    </div>
</x-app-layout>
