@extends('admin.layouts.app')
@section('title', 'ユーザー一覧')

@section('content')
<div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800">
        <span class="text-sm text-gray-500">全 {{ $users->total() }} 件</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-black text-gray-500 text-xs uppercase tracking-wide">
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
            <tbody class="divide-y divide-gray-800">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-900 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-500 text-xs">{{ $user->id }}</td>
                        <td class="px-6 py-4 font-medium text-white">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($user->is_admin)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-900/40 text-rose-400">
                                    管理者
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-800 text-gray-400">
                                    一般
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $user->orders_count }}</td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $user->products_count }}</td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $user->posts_count }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $user->created_at->format('Y/m/d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-600">ユーザーがいません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">{{ $users->links() }}</div>
    @endif
</div>
@endsection
