@extends('admin.layouts.app')
@section('title', '注文一覧')

@section('content')
<div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800">
        <span class="text-sm text-gray-500">全 {{ $orders->total() }} 件</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-black text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">注文ID</th>
                    <th class="px-6 py-3 text-left">購入者</th>
                    <th class="px-6 py-3 text-center">商品数</th>
                    <th class="px-6 py-3 text-right">合計金額</th>
                    <th class="px-6 py-3 text-left">ステータス</th>
                    <th class="px-6 py-3 text-left">注文日時</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse ($orders as $order)
                    @php
                        $statusColors = [
                            0 => 'bg-yellow-900/40 text-yellow-400',
                            1 => 'bg-blue-900/40 text-blue-400',
                            2 => 'bg-indigo-900/40 text-indigo-400',
                            3 => 'bg-green-900/40 text-green-400',
                            4 => 'bg-gray-800 text-gray-500',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-900 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-500 text-xs">#{{ $order->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-white">{{ $order->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $order->items->count() }}点</td>
                        <td class="px-6 py-4 text-right font-bold text-white">
                            ¥{{ number_format($order->total_price) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                         {{ $statusColors[$order->status] ?? 'bg-gray-800 text-gray-500' }}">
                                {{ $order->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $order->created_at->format('Y/m/d H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600">注文がありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
