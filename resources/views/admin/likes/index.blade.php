@extends('admin.layouts.app')
@section('title', 'いいね管理')

@section('content')
<div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-800">
        <span class="text-sm text-gray-500">全 {{ $likes->total() }} 件</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-black text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">いいねしたユーザー</th>
                    <th class="px-6 py-3 text-left">対象投稿（投稿者）</th>
                    <th class="px-6 py-3 text-left">日時</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse ($likes as $like)
                    <tr class="hover:bg-gray-900 transition-colors">
                        <td class="px-6 py-4 font-mono text-gray-500 text-xs">{{ $like->id }}</td>
                        <td class="px-6 py-4 text-gray-300">
                            <a href="{{ route('admin.users.show', $like->user) }}" class="hover:text-[#0095f6] transition-colors">{{ $like->user->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-gray-400 max-w-xs">
                            <a href="{{ route('admin.posts.show', $like->post) }}" class="hover:text-[#0095f6] transition-colors line-clamp-1">
                                {{ mb_substr($like->post->body, 0, 50) }}...
                            </a>
                            <span class="text-gray-600 text-xs">（{{ $like->post->user->name }}）</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $like->created_at->format('Y/m/d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-600">いいねがありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($likes->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">{{ $likes->links() }}</div>
    @endif
</div>
@endsection
