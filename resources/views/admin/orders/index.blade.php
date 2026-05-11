@extends('admin.layouts.app')
@section('title', '注文一覧')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b">
        <span class="text-sm text-gray-500">全 {{ $orders->total() }} 件</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">注文ID</th>
                    <th class="px-6 py-3 text-left">購入者</th>
                    <th class="px-6 py-3 text-center">商品数</th>
                    <th class="px-6 py-3 text-right">合計金額</th>
                    <th class="px-6 py-3 text-left">ステータス</th>
                    <th class="px-6 py-3 text-left">注文日時</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($orders as $order)
                    @php
                        $statusColors = [
                            0 => 'bg-yellow-100 text-yellow-700',
                            1 => 'bg-blue-100 text-blue-700',
                            2 => 'bg-indigo-100 text-indigo-700',
                            3 => 'bg-green-100 text-green-700',
                            4 => 'bg-gray-100 text-gray-500',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-400 text-xs">#{{ $order->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $order->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ $order->items->count() }}点</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                            ¥{{ number_format($order->total_price) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                         {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ $order->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">
                            {{ $order->created_at->format('Y/m/d H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">注文がありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
        <div class="px-6 py-4 border-t">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
