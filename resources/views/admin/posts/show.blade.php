@extends('admin.layouts.app')
@section('title', '投稿詳細')

@section('content')
@if (session('success'))
    <div class="mb-4 px-4 py-3 bg-green-900/40 border border-green-700 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>
@endif

<div class="mb-4">
    <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">← 投稿一覧</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- 投稿内容 --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-[#111111] border border-gray-800 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold text-sm">
                    {{ mb_substr($post->user->name, 0, 1) }}
                </div>
                <div>
                    <a href="{{ route('admin.users.show', $post->user) }}" class="font-semibold text-white hover:text-[#0095f6] transition-colors">{{ $post->user->name }}</a>
                    <p class="text-xs text-gray-500">{{ $post->created_at->format('Y/m/d H:i') }}</p>
                </div>
                <div class="ml-auto">
                    @if ($post->is_hidden)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-900/40 text-orange-400">非公開</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-900/40 text-green-400">公開中</span>
                    @endif
                </div>
            </div>

            <p class="text-gray-200 text-sm whitespace-pre-wrap mb-4">{{ $post->body }}</p>

            @if ($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="投稿画像"
                     class="w-full rounded-lg object-cover max-h-80 mb-4">
            @endif

            @if ($post->product)
                <div class="border border-gray-700 rounded-lg p-3 flex items-center gap-3 bg-black mb-4">
                    <div class="w-12 h-12 bg-gray-800 rounded flex items-center justify-center text-gray-500 text-xs shrink-0">
                        @if ($post->product->image_path)
                            <img src="{{ asset('storage/' . $post->product->image_path) }}" class="w-12 h-12 object-cover rounded">
                        @else
                            商品
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-white">{{ $post->product->title }}</p>
                        <p class="text-xs text-[#0095f6]">¥{{ number_format($post->product->price) }}</p>
                    </div>
                    <a href="{{ route('admin.products.show', $post->product) }}" class="ml-auto text-xs text-[#0095f6] hover:underline">詳細</a>
                </div>
            @endif

            <div class="flex items-center gap-4 text-sm text-gray-500 border-t border-gray-800 pt-3">
                <span>いいね {{ $post->likes->count() }}</span>
                <span>コメント {{ $post->comments->count() }}</span>
            </div>
        </div>

        {{-- コメント一覧 --}}
        @if ($post->comments->isNotEmpty())
            <div class="bg-[#111111] border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-800">
                    <span class="text-sm font-medium text-white">コメント</span>
                </div>
                @foreach ($post->comments as $comment)
                    <div class="px-5 py-3 border-b border-gray-800 last:border-0 flex items-start justify-between gap-4">
                        <div>
                            <a href="{{ route('admin.users.show', $comment->user) }}" class="text-xs font-semibold text-gray-300 hover:text-[#0095f6] transition-colors">{{ $comment->user->name }}</a>
                            <p class="text-sm text-gray-400 mt-0.5">{{ $comment->body }}</p>
                            <p class="text-xs text-gray-600 mt-0.5">{{ $comment->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                              onsubmit="return confirm('このコメントを削除しますか？')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-400 transition-colors shrink-0">削除</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- サイドバー：アクション --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-[#111111] border border-gray-800 rounded-xl p-4 space-y-2">
            <form method="POST" action="{{ route('admin.posts.hide', $post) }}">
                @csrf
                <button type="submit"
                        class="w-full py-2 rounded-lg text-sm font-medium transition-colors
                               {{ $post->is_hidden ? 'bg-green-900/40 text-green-400 hover:bg-green-900/60' : 'bg-orange-900/40 text-orange-400 hover:bg-orange-900/60' }}">
                    {{ $post->is_hidden ? '公開に戻す' : '投稿を非公開化' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                  onsubmit="return confirm('この投稿を削除しますか？')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 bg-red-900/40 hover:bg-red-900/60 text-red-400 rounded-lg text-sm font-medium transition-colors">
                    投稿を削除
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
