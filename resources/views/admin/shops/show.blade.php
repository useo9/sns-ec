@extends('admin.layouts.app')
@section('title', 'ショップ詳細')

@section('content')
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-900/40 border border-green-700 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif

<div class="mb-4">
    <a href="{{ route('admin.shops.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">← ショップ一覧</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- ショップ情報 --}}
    <div class="lg:col-span-1">
        <div class="bg-[#111111] border border-gray-800 rounded-xl p-6 space-y-4">
            <div>
                <p class="font-semibold text-white text-lg">{{ $shop->name }}</p>
                <p class="text-sm text-gray-500">{{ $shop->email }}</p>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">状態</span>
                    @if ($shop->isSuspended())
                        <span class="text-orange-400">停止中</span>
                    @else
                        <span class="text-green-400">公開中</span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">出品数</span>
                    <span class="text-gray-400">{{ $shop->products_count }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">登録日</span>
                    <span class="text-gray-400">{{ $shop->created_at->format('Y/m/d') }}</span>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-800 space-y-2">
                <form method="POST" action="{{ route('admin.shops.suspend', $shop) }}">
                    @csrf
                    <button type="submit"
                            class="w-full py-2 rounded-lg text-sm font-medium transition-colors
                                   {{ $shop->isSuspended() ? 'bg-green-900/40 text-green-400 hover:bg-green-900/60' : 'bg-orange-900/40 text-orange-400 hover:bg-orange-900/60' }}">
                        {{ $shop->isSuspended() ? '停止を解除' : 'ショップを停止' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.shops.destroy', $shop) }}"
                      onsubmit="return confirm('「{{ $shop->name }}」を削除しますか？')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2 bg-red-900/40 hover:bg-red-900/60 text-red-400 rounded-lg text-sm font-medium transition-colors">
                        削除
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- 商品一覧 --}}
    <div class="lg:col-span-2">
        <div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-800">
                <span class="text-sm font-medium text-white">出品商品</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-black text-gray-500 text-xs uppercase tracking-wide">
                        <tr>
                            <th class="px-5 py-3 text-left">商品名</th>
                            <th class="px-5 py-3 text-right">価格</th>
                            <th class="px-5 py-3 text-center">状態</th>
                            <th class="px-5 py-3 text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-900 transition-colors">
                                <td class="px-5 py-3 text-gray-300">{{ $product->title }}</td>
                                <td class="px-5 py-3 text-right text-gray-400">¥{{ number_format($product->price) }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="text-xs {{ $product->status === 1 ? 'text-green-400' : ($product->status === 2 ? 'text-blue-400' : 'text-orange-400') }}">
                                        {{ ['非公開', '出品中', '売却済み'][$product->status] ?? '?' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ route('admin.products.show', $product) }}" class="text-xs text-[#0095f6] hover:underline">詳細</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-600">商品がありません</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($products->hasPages())
                <div class="px-5 py-4 border-t border-gray-800">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
