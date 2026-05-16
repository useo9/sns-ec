<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ url()->previous() }}"
               class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">投稿詳細</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- 投稿カード --}}
            <div class="bg-[#111111] border border-gray-800 rounded-lg overflow-hidden"
                 x-data="{
                     liked: {{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }},
                     likeCount: {{ $post->likes_count }},
                     async toggleLike() {
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
                         this.likeCount = data.count;
                     }
                 }">

                {{-- ユーザー情報 --}}
                <div class="flex items-center gap-3 px-4 pt-4">
                    <a href="{{ route('users.show', $post->user) }}"
                       class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-sm shrink-0 hover:opacity-80 transition-opacity">
                        {{ mb_substr($post->user->name, 0, 1) }}
                    </a>
                    <div class="flex-1">
                        <a href="{{ route('users.show', $post->user) }}"
                           class="font-semibold text-sm text-white hover:underline">{{ $post->user->name }}</a>
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

                {{-- 本文 --}}
                <p class="px-4 pt-3 pb-3 text-gray-200 text-sm whitespace-pre-wrap leading-relaxed">{{ $post->body }}</p>

                {{-- 投稿画像 --}}
                @if ($post->image_path)
                    <img src="{{ image_url($post->image_path) }}"
                         alt="投稿画像"
                         class="w-full object-cover">
                @endif

                {{-- リンク商品カード --}}
                @if ($post->product)
                    <a href="{{ route('products.show', $post->product) }}"
                       class="mx-4 my-3 border border-gray-700 rounded-lg flex items-center gap-3 p-3 bg-black hover:bg-gray-900 transition-colors block">
                        @if ($post->product->image_path)
                            <img src="{{ image_url($post->product->image_path) }}"
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

                {{-- タグ --}}
                @if ($post->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-1 px-4 pb-3">
                        @foreach ($post->tags as $tag)
                            <span class="text-xs bg-gray-800 text-gray-400 rounded-full px-2 py-0.5">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- いいねバー --}}
                <div class="px-4 py-3 border-t border-gray-800 flex items-center gap-5">
                    <button @click="toggleLike"
                            class="flex items-center gap-1.5 text-sm transition-colors"
                            :class="liked ? 'text-rose-400' : 'text-gray-500 hover:text-rose-400'">
                        <svg class="w-5 h-5 transition-transform active:scale-125"
                             :fill="liked ? 'currentColor' : 'none'"
                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span x-text="likeCount"></span>
                    </button>
                    <div class="flex items-center gap-1.5 text-sm text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>{{ $post->comments->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- コメントセクション --}}
            <div class="bg-[#111111] border border-gray-800 rounded-lg overflow-hidden"
                 x-data="{
                     comments: @json($post->comments->map(fn($c) => ['id' => $c->id, 'body' => $c->body, 'user_id' => $c->user_id, 'user_name' => $c->user->name, 'created_at' => $c->created_at->diffForHumans()])),
                     newComment: '',
                     authId: {{ auth()->id() }},
                     submitting: false,
                     async submit() {
                         if (!this.newComment.trim() || this.submitting) return;
                         this.submitting = true;
                         const res = await fetch('/posts/{{ $post->id }}/comments', {
                             method: 'POST',
                             headers: {
                                 'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
                                 'Content-Type': 'application/json',
                                 'Accept': 'application/json',
                             },
                             body: JSON.stringify({ body: this.newComment })
                         });
                         if (res.ok) {
                             const data = await res.json();
                             this.comments.push(data);
                             this.newComment = '';
                         }
                         this.submitting = false;
                     },
                     async deleteComment(id) {
                         if (!confirm('コメントを削除しますか？')) return;
                         const res = await fetch('/comments/' + id, {
                             method: 'DELETE',
                             headers: {
                                 'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
                                 'Accept': 'application/json',
                             }
                         });
                         if (res.ok) {
                             this.comments = this.comments.filter(c => c.id !== id);
                         }
                     }
                 }">

                <div class="px-4 pt-4 pb-2">
                    <h3 class="text-sm font-semibold text-white">コメント</h3>
                </div>

                {{-- コメント入力欄 --}}
                <div class="px-4 pb-4 border-b border-gray-800">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-xs shrink-0 mt-1">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <textarea x-model="newComment"
                                      @keydown.ctrl.enter.prevent="submit"
                                      @keydown.meta.enter.prevent="submit"
                                      placeholder="コメントを入力..."
                                      rows="3"
                                      class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-gray-500 resize-none transition-colors"></textarea>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-gray-600">Ctrl + Enter で送信</p>
                                <button @click="submit"
                                        :disabled="!newComment.trim() || submitting"
                                        class="px-4 py-1.5 bg-[#0095f6] hover:bg-[#1aa3ff] disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-semibold rounded-lg transition-colors">
                                    <span x-show="!submitting">送信</span>
                                    <span x-show="submitting">送信中...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- コメント一覧 --}}
                <div class="divide-y divide-gray-800/50">
                    <template x-if="comments.length === 0">
                        <div class="px-4 py-8 text-center text-gray-600 text-sm">
                            まだコメントがありません。最初にコメントしてみましょう！
                        </div>
                    </template>
                    <template x-for="comment in comments" :key="comment.id">
                        <div class="flex items-start gap-3 px-4 py-3">
                            <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-xs shrink-0"
                                 x-text="comment.user_name.charAt(0)"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-gray-200" x-text="comment.user_name"></span>
                                    <span class="text-xs text-gray-600" x-text="comment.created_at || ''"></span>
                                </div>
                                <p class="text-sm text-gray-300 mt-1 break-words leading-relaxed" x-text="comment.body"></p>
                            </div>
                            <button x-show="comment.user_id === authId"
                                    @click="deleteComment(comment.id)"
                                    class="p-1.5 text-gray-600 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-colors shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
