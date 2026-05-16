@extends('admin.layouts.app')
@section('title', 'ユーザー管理')

@section('content')
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-900/40 border border-green-700 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 px-4 py-3 bg-red-900/40 border border-red-700 rounded-lg text-red-400 text-sm">{{ session('error') }}</div>
@endif

{{-- 検索・フィルター --}}
<form method="GET" class="mb-4 flex gap-3">
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="名前・メールで検索..."
           class="flex-1 bg-[#111111] border border-gray-700 rounded-lg px-4 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
    <select name="filter" class="bg-[#111111] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-gray-500">
        <option value="">すべて</option>
        <option value="suspended" @selected(request('filter') === 'suspended')>停止中</option>
        <option value="admin" @selected(request('filter') === 'admin')>管理者</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-[#0095f6] hover:bg-[#1aa3ff] text-white text-sm rounded-lg transition-colors">検索</button>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm rounded-lg transition-colors">リセット</a>
</form>

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
                    <th class="px-6 py-3 text-center">状態</th>
                    <th class="px-6 py-3 text-center">注文数</th>
                    <th class="px-6 py-3 text-center">出品数</th>
                    <th class="px-6 py-3 text-center">投稿数</th>
                    <th class="px-6 py-3 text-left">登録日</th>
                    <th class="px-6 py-3 text-center">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-900 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-500 text-xs">{{ $user->id }}</td>
                        <td class="px-6 py-4 font-medium text-white">
                            <a href="{{ route('admin.users.show', $user) }}" class="hover:text-[#0095f6] transition-colors">{{ $user->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($user->is_admin)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-900/40 text-rose-400">管理者</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-800 text-gray-400">一般</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($user->isSuspended())
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-900/40 text-orange-400">停止中</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-900/40 text-green-400">正常</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $user->orders_count }}</td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $user->products_count }}</td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $user->posts_count }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $user->created_at->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-xs text-[#0095f6] hover:underline">詳細</a>
                                @unless ($user->is_admin)
                                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                        @csrf
                                        <button type="submit" class="text-xs {{ $user->isSuspended() ? 'text-green-400 hover:text-green-300' : 'text-orange-400 hover:text-orange-300' }} transition-colors">
                                            {{ $user->isSuspended() ? '解除' : '停止' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('「{{ $user->name }}」を削除しますか？')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-400 transition-colors">削除</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-600">ユーザーが見つかりません</td>
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
