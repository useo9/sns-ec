<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">ユーザープロフィール</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- プロフィールカード --}}
            <div class="bg-[#111111] border border-gray-800 rounded-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-xl shrink-0">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-lg">{{ $user->name }}</p>
                        <div class="flex gap-4 mt-1 text-sm text-gray-400">
                            <span><span class="text-white font-semibold">{{ $followersCount }}</span> フォロワー</span>
                            <span><span class="text-white font-semibold">{{ $followingCount }}</span> フォロー中</span>
                        </div>
                    </div>

                    {{-- フォローボタン (自分自身には表示しない) --}}
                    @if (auth()->id() !== $user->id)
                        @if ($isFollowing)
                            <form method="POST" action="{{ route('users.unfollow', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-5 py-2 rounded-lg text-sm font-medium bg-gray-700 text-white hover:bg-gray-600 transition-colors border border-gray-600">
                                    フォロー中
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('users.follow', $user) }}">
                                @csrf
                                <button type="submit"
                                        class="px-5 py-2 rounded-lg text-sm font-medium bg-[#0095f6] text-white hover:bg-[#1aa3ff] transition-colors">
                                    フォロー
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            {{-- 投稿一覧 --}}
            @forelse ($posts as $post)
                <article class="bg-[#111111] shadow-sm rounded-lg overflow-hidden border border-gray-800">
                    <div class="flex items-center gap-3 px-4 pt-4">
                        <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-sm shrink-0">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-white">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                        @if ($post->user_id === auth()->id())
                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                  onsubmit="return confirm('この投稿を削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-gray-500 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-colors"
                                        title="削除">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>

                    <p class="px-4 pt-3 pb-2 text-gray-200 text-sm whitespace-pre-wrap">{{ $post->body }}</p>

                    @if ($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}"
                             alt="投稿画像"
                             class="w-full object-cover max-h-80">
                    @endif

                    @if ($post->product)
                        <a href="{{ route('products.show', $post->product) }}"
                           class="mx-4 mb-3 border border-gray-700 rounded-lg flex items-center gap-3 p-3 bg-black hover:bg-gray-900 transition-colors block">
                            @if ($post->product->image_path)
                                <img src="{{ asset('storage/' . $post->product->image_path) }}"
                                     alt="{{ $post->product->title }}"
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-800 rounded flex items-center justify-center text-gray-500 text-xs">No Image</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $post->product->title }}</p>
                                <p class="text-sm text-[#0095f6] font-bold">¥{{ number_format($post->product->price) }}</p>
                            </div>
                        </a>
                    @endif

                    @if ($post->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-1 px-4 pb-2">
                            @foreach ($post->tags as $tag)
                                <span class="text-xs bg-gray-800 text-gray-400 rounded-full px-2 py-0.5">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="px-4 pb-3 border-t border-gray-800 pt-2"
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
                                :class="liked ? 'text-rose-400' : 'text-gray-600 hover:text-rose-400'">
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
                <div class="text-center text-gray-600 py-16">
                    まだ投稿がありません
                </div>
            @endforelse

            @if ($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
