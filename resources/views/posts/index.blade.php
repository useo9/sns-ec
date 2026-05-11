<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">タイムライン</h2>
            <a href="{{ route('posts.create') }}"
               class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                投稿する
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($posts as $post)
                <article class="bg-white shadow-sm rounded-lg overflow-hidden">
                    {{-- ユーザー情報 --}}
                    <div class="flex items-center gap-3 px-4 pt-4">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm shrink-0">
                            {{ mb_substr($post->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-gray-800">{{ $post->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- 本文 --}}
                    <p class="px-4 pt-3 pb-2 text-gray-700 text-sm whitespace-pre-wrap">{{ $post->body }}</p>

                    {{-- 投稿画像 --}}
                    @if ($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}"
                             alt="投稿画像"
                             class="w-full object-cover max-h-80">
                    @endif

                    {{-- リンク商品カード --}}
                    @if ($post->product)
                        <a href="{{ route('products.show', $post->product) }}"
                           class="mx-4 mb-3 border rounded-lg flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 transition-colors block">
                            @if ($post->product->image_path)
                                <img src="{{ asset('storage/' . $post->product->image_path) }}"
                                     alt="{{ $post->product->title }}"
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">No Image</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $post->product->title }}</p>
                                <p class="text-sm text-rose-600 font-bold">¥{{ number_format($post->product->price) }}</p>
                            </div>
                        </a>
                    @endif

                    {{-- タグ --}}
                    @if ($post->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-1 px-4 pb-2">
                            @foreach ($post->tags as $tag)
                                <span class="text-xs bg-gray-100 text-gray-600 rounded-full px-2 py-0.5">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- いいね --}}
                    <div class="px-4 pb-3 border-t pt-2"
                         x-data="{
                             liked: {{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }},
                             count: {{ $post->likes_count }},
                             async toggle() {
                                 const method = this.liked ? 'DELETE' : 'POST';
                                 const res = await fetch('/posts/{{ $post->id }}/like', {
                                     method,
                                     headers: {
                                         'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
                                         'Accept': 'application/json',
                                     }
                                 });
                                 const data = await res.json();
                                 this.liked = data.liked;
                                 this.count = data.count;
                             }
                         }">
                        <button @click="toggle"
                                class="flex items-center gap-1.5 text-sm transition-colors"
                                :class="liked ? 'text-rose-500' : 'text-gray-400 hover:text-rose-400'">
                            <svg class="w-5 h-5 transition-transform active:scale-125"
                                 :fill="liked ? 'currentColor' : 'none'"
                                 stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span x-text="count"></span>
                        </button>
                    </div>
                </article>
            @empty
                <div class="text-center text-gray-400 py-16">
                    まだ投稿がありません
                </div>
            @endforelse

            {{-- ページネーション --}}
            @if ($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
