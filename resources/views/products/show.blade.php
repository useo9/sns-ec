<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

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

            <div class="bg-[#111111] rounded-lg overflow-hidden border border-gray-800">
                <div class="md:flex">

                    {{-- 画像ギャラリー --}}
                    @php $images = $product->productImages; @endphp
                    <div class="md:w-1/2 shrink-0"
                         x-data="{ current: 0, images: {{ $images->isNotEmpty() ? $images->pluck('image_path')->toJson() : '[]' }} }">

                        @if ($images->isNotEmpty())
                            {{-- メイン画像 --}}
                            <div class="relative w-full aspect-square bg-gray-900">
                                <template x-for="(path, i) in images" :key="i">
                                    <img :src="'/storage/' + path"
                                         x-show="current === i"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         class="absolute inset-0 w-full h-full object-cover">
                                </template>

                                {{-- 前後ボタン --}}
                                <template x-if="images.length > 1">
                                    <div>
                                        <button @click="current = (current - 1 + images.length) % images.length"
                                                class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-black/50 rounded-full flex items-center justify-center text-white hover:bg-black/70 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button @click="current = (current + 1) % images.length"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-black/50 rounded-full flex items-center justify-center text-white hover:bg-black/70 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                {{-- インジケーター --}}
                                <template x-if="images.length > 1">
                                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
                                        <template x-for="(_, i) in images" :key="i">
                                            <button @click="current = i"
                                                    class="w-1.5 h-1.5 rounded-full transition-colors"
                                                    :class="current === i ? 'bg-white' : 'bg-white/40'"></button>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            {{-- サムネイル --}}
                            @if ($images->count() > 1)
                                <div class="flex gap-1 p-2 bg-gray-900/50 overflow-x-auto">
                                    <template x-for="(path, i) in images" :key="i">
                                        <button @click="current = i"
                                                class="shrink-0 w-14 h-14 rounded overflow-hidden border-2 transition-colors"
                                                :class="current === i ? 'border-[#0095f6]' : 'border-transparent'">
                                            <img :src="'/storage/' + path" class="w-full h-full object-cover">
                                        </button>
                                    </template>
                                </div>
                            @endif

                        @elseif ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                 alt="{{ $product->title }}"
                                 class="w-full aspect-square object-cover">
                        @else
                            <div class="w-full aspect-square bg-gray-800 flex items-center justify-center text-gray-500">
                                No Image
                            </div>
                        @endif
                    </div>

                    {{-- 商品情報 --}}
                    <div class="md:w-1/2 p-6 flex flex-col">
                        <div class="flex items-start justify-between gap-2">
                            <h1 class="text-xl font-bold text-white">{{ $product->title }}</h1>
                            @if (auth()->id() === $product->user_id)
                                <a href="{{ route('products.edit', $product) }}"
                                   class="shrink-0 px-3 py-1.5 text-xs text-gray-400 border border-gray-700 rounded-lg hover:text-white hover:border-gray-500 transition-colors">
                                    編集
                                </a>
                            @endif
                        </div>
                        <p class="text-2xl font-bold text-white mt-2">¥{{ number_format($product->price) }}</p>

                        <dl class="mt-4 space-y-2 text-sm">
                            @if ($product->brand)
                                <div class="flex">
                                    <dt class="w-24 text-gray-500 shrink-0">ブランド</dt>
                                    <dd class="text-gray-200">{{ $product->brand }}</dd>
                                </div>
                            @endif
                            @if ($product->size)
                                <div class="flex">
                                    <dt class="w-24 text-gray-500 shrink-0">サイズ</dt>
                                    <dd class="text-gray-200">{{ $product->size }}</dd>
                                </div>
                            @endif
                            @if ($product->category)
                                <div class="flex">
                                    <dt class="w-24 text-gray-500 shrink-0">カテゴリ</dt>
                                    <dd class="text-gray-200">{{ $product->category }}</dd>
                                </div>
                            @endif
                            <div class="flex">
                                <dt class="w-24 text-gray-500 shrink-0">商品の状態</dt>
                                <dd class="text-gray-200">{{ $product->conditionLabel() }}</dd>
                            </div>
                        </dl>

                        @if ($product->tags->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach ($product->tags as $tag)
                                    <span class="text-xs bg-gray-800 text-gray-400 rounded-full px-2 py-0.5">
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
                                            class="w-full bg-[#0095f6] hover:bg-[#1aa3ff] text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                        カートに追加する
                                    </button>
                                </form>
                            @elseif (! $product->isAvailable())
                                <div class="w-full bg-gray-700 text-gray-400 font-bold py-3 px-4 rounded-lg text-center">
                                    売り切れ
                                </div>
                            @else
                                <div class="w-full bg-gray-800 text-gray-500 font-bold py-3 px-4 rounded-lg text-center">
                                    自分の出品商品です
                                </div>
                            @endif
                        </div>

                        {{-- 出品者 --}}
                        <div class="mt-4 flex items-center gap-2 border-t border-gray-800 pt-4">
                            <a href="{{ route('users.show', $product->user) }}"
                               class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-xs font-bold text-gray-300 shrink-0 hover:opacity-80 transition-opacity">
                                {{ mb_substr($product->user->name, 0, 1) }}
                            </a>
                            <a href="{{ route('users.show', $product->user) }}"
                               class="text-sm text-gray-400 hover:text-white transition-colors">
                                {{ $product->user->name }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 商品説明 --}}
                @if ($product->description)
                    <div class="p-6 border-t border-gray-800">
                        <h2 class="font-semibold text-white mb-2">商品説明</h2>
                        <p class="text-sm text-gray-300 whitespace-pre-wrap">{{ $product->description }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="text-sm text-gray-400 hover:underline">← 商品一覧へ戻る</a>
            </div>
        </div>
    </div>
</x-app-layout>
