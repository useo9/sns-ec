@extends('admin.layouts.app')
@section('title', '商品詳細')

@section('content')
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-900/40 border border-green-700 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif

<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">← 商品一覧</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- 画像 --}}
    <div class="lg:col-span-1 space-y-4">
        @if ($product->image_path)
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}"
                 class="w-full rounded-xl object-cover aspect-square border border-gray-800">
        @elseif ($product->productImages->isNotEmpty())
            <img src="{{ asset('storage/' . $product->productImages->first()->image_path) }}" alt="{{ $product->title }}"
                 class="w-full rounded-xl object-cover aspect-square border border-gray-800">
        @else
            <div class="w-full aspect-square rounded-xl bg-gray-800 flex items-center justify-center text-gray-600">No Image</div>
        @endif

        {{-- アクション --}}
        <div class="bg-[#111111] border border-gray-800 rounded-xl p-4 space-y-2">
            @if ($product->status !== 2)
                <form method="POST" action="{{ route('admin.products.suspend', $product) }}">
                    @csrf
                    <button type="submit"
                            class="w-full py-2 rounded-lg text-sm font-medium transition-colors
                                   {{ $product->status === 0 ? 'bg-green-900/40 text-green-400 hover:bg-green-900/60' : 'bg-orange-900/40 text-orange-400 hover:bg-orange-900/60' }}">
                        {{ $product->status === 0 ? '公開に戻す' : '出品を停止（非公開）' }}
                    </button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                  onsubmit="return confirm('「{{ $product->title }}」を削除しますか？')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 bg-red-900/40 hover:bg-red-900/60 text-red-400 rounded-lg text-sm font-medium transition-colors">
                    商品を削除
                </button>
            </form>
        </div>
    </div>

    {{-- 詳細情報 --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-[#111111] border border-gray-800 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-1">{{ $product->title }}</h2>
            <p class="text-2xl font-bold text-[#0095f6] mb-4">¥{{ number_format($product->price) }}</p>

            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <span class="text-gray-500">出品者</span>
                    <a href="{{ route('admin.users.show', $product->user) }}" class="block text-white hover:text-[#0095f6] transition-colors mt-0.5">{{ $product->user->name }}</a>
                </div>
                <div>
                    <span class="text-gray-500">状態</span>
                    <p class="text-white mt-0.5">
                        @if ($product->status === 1)
                            <span class="text-green-400">出品中</span>
                        @elseif ($product->status === 2)
                            <span class="text-blue-400">売却済み</span>
                        @else
                            <span class="text-orange-400">非公開</span>
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-gray-500">ブランド</span>
                    <p class="text-white mt-0.5">{{ $product->brand ?: '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">サイズ</span>
                    <p class="text-white mt-0.5">{{ $product->size ?: '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">カテゴリ</span>
                    <p class="text-white mt-0.5">{{ $product->category ?: '—' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">商品の状態</span>
                    <p class="text-white mt-0.5">{{ $product->conditionLabel() }}</p>
                </div>
                <div>
                    <span class="text-gray-500">出品日</span>
                    <p class="text-white mt-0.5">{{ $product->created_at->format('Y/m/d') }}</p>
                </div>
            </div>

            @if ($product->tags->isNotEmpty())
                <div class="flex flex-wrap gap-1 mb-4">
                    @foreach ($product->tags as $tag)
                        <span class="text-xs bg-gray-800 text-gray-400 rounded-full px-2 py-0.5">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            @if ($product->description)
                <div class="border-t border-gray-800 pt-4">
                    <p class="text-sm text-gray-500 mb-1">説明</p>
                    <p class="text-sm text-gray-300 whitespace-pre-wrap">{{ $product->description }}</p>
                </div>
            @endif
        </div>

        {{-- 購入履歴 --}}
        @if ($product->orderItems->isNotEmpty())
            <div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-800">
                    <span class="text-sm font-medium text-white">購入履歴</span>
                </div>
                @foreach ($product->orderItems as $item)
                    <div class="px-5 py-3 border-b border-gray-800 last:border-0 flex justify-between items-center text-sm">
                        <div>
                            <p class="text-gray-300">{{ $item->order->user->name }}</p>
                            <p class="text-xs text-gray-600">{{ $item->order->created_at->format('Y/m/d') }}</p>
                        </div>
                        <span class="text-gray-400">¥{{ number_format($item->price) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
