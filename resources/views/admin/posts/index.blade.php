@extends('admin.layouts.app')
@section('title', '投稿管理')

@section('content')
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-900/40 border border-green-700 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif

<form method="GET" class="mb-4 flex gap-3">
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="本文で検索..."
           class="flex-1 bg-[#111111] border border-gray-700 rounded-lg px-4 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
    <select name="filter" class="bg-[#111111] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-gray-500">
        <option value="">すべて</option>
        <option value="visible" @selected(request('filter') === 'visible')>公開中</option>
        <option value="hidden" @selected(request('filter') === 'hidden')>非公開</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-[#0095f6] hover:bg-[#1aa3ff] text-white text-sm rounded-lg transition-colors">検索</button>
    <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm rounded-lg transition-colors">リセット</a>
</form>

<div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800">
        <span class="text-sm text-gray-500">全 {{ $posts->total() }} 件</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-black text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">本文（抜粋）</th>
                    <th class="px-6 py-3 text-left">投稿者</th>
                    <th class="px-6 py-3 text-center">いいね</th>
                    <th class="px-6 py-3 text-center">コメント</th>
                    <th class="px-6 py-3 text-center">公開状態</th>
                    <th class="px-6 py-3 text-left">投稿日</th>
                    <th class="px-6 py-3 text-center">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse ($posts as $post)
                    <tr class="hover:bg-gray-900 transition-colors {{ $post->is_hidden ? 'opacity-60' : '' }}">
                        <td class="px-6 py-4 font-mono text-gray-500 text-xs">{{ $post->id }}</td>
                        <td class="px-6 py-4 text-gray-300 max-w-xs">
                            <a href="{{ route('admin.posts.show', $post) }}" class="hover:text-[#0095f6] transition-colors line-clamp-2">{{ $post->body }}</a>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            <a href="{{ route('admin.users.show', $post->user) }}" class="hover:text-[#0095f6] transition-colors">{{ $post->user->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $post->likes_count }}</td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $post->comments_count }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($post->is_hidden)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-900/40 text-orange-400">非公開</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-900/40 text-green-400">公開中</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $post->created_at->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.posts.show', $post) }}" class="text-xs text-[#0095f6] hover:underline">詳細</a>
                                <form method="POST" action="{{ route('admin.posts.hide', $post) }}">
                                    @csrf
                                    <button type="submit" class="text-xs {{ $post->is_hidden ? 'text-green-400 hover:text-green-300' : 'text-orange-400 hover:text-orange-300' }} transition-colors">
                                        {{ $post->is_hidden ? '公開' : '非公開' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                      onsubmit="return confirm('この投稿を削除しますか？')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-400 transition-colors">削除</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-600">投稿が見つかりません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($posts->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">{{ $posts->links() }}</div>
    @endif
</div>
@endsection
