@extends('admin.layouts.app')
@section('title', 'ユーザー詳細')

@section('content')
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-900/40 border border-green-700 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 px-4 py-3 bg-red-900/40 border border-red-700 rounded-lg text-red-400 text-sm">{{ session('error') }}</div>
@endif

<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">← ユーザー一覧</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- プロフィールカード --}}
    <div class="lg:col-span-1">
        <div class="bg-[#111111] border border-gray-800 rounded-xl p-6 space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold text-xl">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-white">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">権限</span>
                    @if ($user->is_admin)
                        <span class="text-rose-400">管理者</span>
                    @else
                        <span class="text-gray-400">一般ユーザー</span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">状態</span>
                    @if ($user->isSuspended())
                        <span class="text-orange-400">停止中（{{ $user->suspended_at->format('Y/m/d') }}〜）</span>
                    @else
                        <span class="text-green-400">正常</span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">登録日</span>
                    <span class="text-gray-400">{{ $user->created_at->format('Y/m/d') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">メール認証</span>
                    <span class="{{ $user->email_verified_at ? 'text-green-400' : 'text-red-400' }}">
                        {{ $user->email_verified_at ? '済み' : '未認証' }}
                    </span>
                </div>
            </div>

            {{-- 統計 --}}
            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-800">
                @foreach ([['フォロワー', $user->followers_count], ['フォロー中', $user->following_count], ['投稿数', $user->posts_count], ['出品数', $user->products_count], ['注文数', $user->orders_count]] as [$label, $count])
                    <div class="bg-black rounded-lg px-3 py-2 text-center">
                        <p class="text-lg font-bold text-white">{{ $count }}</p>
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                    </div>
                @endforeach
            </div>

            {{-- アクション --}}
            @unless ($user->is_admin)
                <div class="pt-3 border-t border-gray-800 space-y-2">
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-2 rounded-lg text-sm font-medium transition-colors
                                       {{ $user->isSuspended() ? 'bg-green-900/40 text-green-400 hover:bg-green-900/60' : 'bg-orange-900/40 text-orange-400 hover:bg-orange-900/60' }}">
                            {{ $user->isSuspended() ? 'アカウント停止を解除' : 'アカウントを停止' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('「{{ $user->name }}」を完全に削除しますか？この操作は元に戻せません。')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-red-900/40 hover:bg-red-900/60 text-red-400 rounded-lg text-sm font-medium transition-colors">
                            アカウントを削除
                        </button>
                    </form>
                </div>
            @endunless
        </div>
    </div>

    {{-- 最近の活動 --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- 最近の投稿 --}}
        <div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-800">
                <span class="text-sm font-medium text-white">最近の投稿</span>
            </div>
            @forelse ($recentPosts as $post)
                <div class="px-5 py-3 border-b border-gray-800 last:border-0 flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-300 line-clamp-1">{{ $post->body }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $post->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                    <a href="{{ route('admin.posts.show', $post) }}" class="text-xs text-[#0095f6] hover:underline shrink-0 ml-4">詳細</a>
                </div>
            @empty
                <p class="px-5 py-4 text-sm text-gray-600">投稿がありません</p>
            @endforelse
        </div>

        {{-- 最近の出品 --}}
        <div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-800">
                <span class="text-sm font-medium text-white">最近の出品</span>
            </div>
            @forelse ($recentProducts as $product)
                <div class="px-5 py-3 border-b border-gray-800 last:border-0 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-300">{{ $product->title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">¥{{ number_format($product->price) }}
                            <span class="ml-2 {{ $product->status === 1 ? 'text-green-400' : ($product->status === 2 ? 'text-blue-400' : 'text-orange-400') }}">
                                {{ ['非公開', '出品中', '売却済み'][$product->status] ?? '?' }}
                            </span>
                        </p>
                    </div>
                    <a href="{{ route('admin.products.show', $product) }}" class="text-xs text-[#0095f6] hover:underline shrink-0 ml-4">詳細</a>
                </div>
            @empty
                <p class="px-5 py-4 text-sm text-gray-600">出品がありません</p>
            @endforelse
        </div>

        {{-- 最近の注文 --}}
        <div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-800">
                <span class="text-sm font-medium text-white">最近の注文</span>
            </div>
            @forelse ($recentOrders as $order)
                <div class="px-5 py-3 border-b border-gray-800 last:border-0 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-300">注文 #{{ $order->id }} — ¥{{ number_format($order->total_price) }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $order->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $order->statusLabel() }}</span>
                </div>
            @empty
                <p class="px-5 py-4 text-sm text-gray-600">注文がありません</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
