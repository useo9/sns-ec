@extends('admin.layouts.app')
@section('title', 'ユーザー一覧')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b">
        <span class="text-sm text-gray-500">全 {{ $users->total() }} 件</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">名前</th>
                    <th class="px-6 py-3 text-left">メールアドレス</th>
                    <th class="px-6 py-3 text-center">権限</th>
                    <th class="px-6 py-3 text-center">注文数</th>
                    <th class="px-6 py-3 text-center">出品数</th>
                    <th class="px-6 py-3 text-center">投稿数</th>
                    <th class="px-6 py-3 text-left">登録日</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-400 text-xs">{{ $user->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($user->is_admin)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-700">
                                    管理者
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                    一般
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ $user->orders_count }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ $user->products_count }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ $user->posts_count }}</td>
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $user->created_at->format('Y/m/d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">ユーザーがいません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="px-6 py-4 border-t">{{ $users->links() }}</div>
    @endif
</div>
@endsection
