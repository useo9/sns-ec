@extends('admin.layouts.app')
@section('title', 'コメント管理')

@section('content')
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-900/40 border border-green-700 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif

<form method="GET" class="mb-4 flex gap-3">
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="コメント内容で検索..."
           class="flex-1 bg-[#111111] border border-gray-700 rounded-lg px-4 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gray-500">
    <button type="submit" class="px-4 py-2 bg-[#0095f6] hover:bg-[#1aa3ff] text-white text-sm rounded-lg transition-colors">検索</button>
    <a href="{{ route('admin.comments.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm rounded-lg transition-colors">リセット</a>
</form>

<div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800">
        <span class="text-sm text-gray-500">全 {{ $comments->total() }} 件</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-black text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">コメント内容</th>
                    <th class="px-6 py-3 text-left">投稿者</th>
                    <th class="px-6 py-3 text-left">投稿（対象）</th>
                    <th class="px-6 py-3 text-left">日時</th>
                    <th class="px-6 py-3 text-center">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse ($comments as $comment)
                    <tr class="hover:bg-gray-900 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-500 text-xs">{{ $comment->id }}</td>
                        <td class="px-6 py-4 text-gray-300 max-w-xs">
                            <p class="line-clamp-2">{{ $comment->body }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-400">
                            <a href="{{ route('admin.users.show', $comment->user) }}" class="hover:text-[#0095f6] transition-colors">{{ $comment->user->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs max-w-xs">
                            <a href="{{ route('admin.posts.show', $comment->post) }}" class="hover:text-[#0095f6] transition-colors line-clamp-1">
                                {{ mb_substr($comment->post->body, 0, 40) }}...
                            </a>
                            <span class="text-gray-600">（{{ $comment->post->user->name }}）</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $comment->created_at->format('Y/m/d H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                                  onsubmit="return confirm('このコメントを削除しますか？')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-400 transition-colors">削除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-600">コメントが見つかりません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($comments->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">{{ $comments->links() }}</div>
    @endif
</div>
@endsection
