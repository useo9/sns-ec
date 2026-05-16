@extends('admin.layouts.app')
@section('title', '商品管理')

@section('content')
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-900/40 border border-green-700 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif

<form method="GET" class="mb-4 flex gap-3">
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="商品名・ブランドで検索..."
           class="flex-1 bg-[#111111] border border-gray-700 rounded-lg px-4 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
    <select name="status" class="bg-[#111111] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-gray-500">
        <option value="">すべて</option>
        <option value="1" @selected(request('status') === '1')>出品中</option>
        <option value="0" @selected(request('status') === '0')>非公開</option>
        <option value="2" @selected(request('status') === '2')>売却済み</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-[#0095f6] hover:bg-[#1aa3ff] text-white text-sm rounded-lg transition-colors">検索</button>
    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm rounded-lg transition-colors">リセット</a>
</form>

<div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800">
        <span class="text-sm text-gray-500">全 {{ $products->total() }} 件</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-black text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">商品名</th>
                    <th class="px-6 py-3 text-left">出品者</th>
                    <th class="px-6 py-3 text-right">価格</th>
                    <th class="px-6 py-3 text-center">状態</th>
                    <th class="px-6 py-3 text-left">出品日</th>
                    <th class="px-6 py-3 text-center">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-900 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-500 text-xs">{{ $product->id }}</td>
                        <td class="px-6 py-4 text-white">
                            <a href="{{ route('admin.products.show', $product) }}" class="hover:text-[#0095f6] transition-colors line-clamp-1">{{ $product->title }}</a>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            <a href="{{ route('admin.users.show', $product->user) }}" class="hover:text-[#0095f6] transition-colors">{{ $product->user->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-right text-gray-300">¥{{ number_format($product->price) }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($product->status === 1)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-900/40 text-green-400">出品中</span>
                            @elseif ($product->status === 2)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-900/40 text-blue-400">売却済み</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-900/40 text-orange-400">非公開</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $product->created_at->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.products.show', $product) }}" class="text-xs text-[#0095f6] hover:underline">詳細</a>
                                @if ($product->status !== 2)
                                    <form method="POST" action="{{ route('admin.products.suspend', $product) }}">
                                        @csrf
                                        <button type="submit" class="text-xs {{ $product->status === 0 ? 'text-green-400 hover:text-green-300' : 'text-orange-400 hover:text-orange-300' }} transition-colors">
                                            {{ $product->status === 0 ? '公開' : '非公開' }}
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('「{{ $product->title }}」を削除しますか？')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-400 transition-colors">削除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-600">商品が見つかりません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">{{ $products->links() }}</div>
    @endif
</div>
@endsection
