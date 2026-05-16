@php
    $commentsData = $post->comments->map(function ($c) {
        return ['id' => $c->id, 'body' => $c->body, 'user_id' => $c->user_id, 'user_name' => $c->user->name];
    });
@endphp
<article class="bg-[#111111] shadow-sm rounded-lg overflow-hidden border border-gray-800"
         x-data="{
             liked: {{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }},
             likeCount: {{ $post->likes_count }},
             showComments: false,
             comments: @json($commentsData),
             newComment: '',
             authId: {{ auth()->id() }},
             originalBody: @json($post->body),
             translatedBody: null,
             showTranslated: false,
             translating: false,
             async translate() {
                 if (this.translatedBody) { this.showTranslated = !this.showTranslated; return; }
                 this.translating = true;
                 try {
                     const res = await fetch('/translate', {
                         method: 'POST',
                         headers: {
                             'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
                             'Content-Type': 'application/json',
                             'Accept': 'application/json',
                         },
                         body: JSON.stringify({ text: this.originalBody.substring(0, 500) })
                     });
                     if (res.ok) {
                         const data = await res.json();
                         this.translatedBody = data.translated;
                         this.showTranslated = true;
                     }
                 } finally {
                     this.translating = false;
                 }
             },
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
             },
             async submitComment() {
                 if (!this.newComment.trim()) return;
                 const res = await fetch('/posts/{{ $post->id }}/comments', {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
                         'Content-Type': 'application/json',
                         'Accept': 'application/json',
                     },
                     body: JSON.stringify({ body: this.newComment })
                 });
                 if (!res.ok) return;
                 const data = await res.json();
                 this.comments.unshift(data);
                 this.newComment = '';
             },
             async deleteComment(id) {
                 await fetch('/comments/' + id, {
                     method: 'DELETE',
                     headers: {
                         'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
                         'Accept': 'application/json',
                     }
                 });
                 this.comments = this.comments.filter(c => c.id !== id);
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
    <p class="px-4 pt-3 pb-2 text-gray-200 text-sm whitespace-pre-wrap"
       x-text="showTranslated && translatedBody ? translatedBody : originalBody"></p>

    {{-- 投稿画像（複数対応） --}}
    @php $postImgs = $post->postImages->isNotEmpty() ? $post->postImages->pluck('image_path') : ($post->image_path ? collect([$post->image_path]) : collect()); @endphp
    @if ($postImgs->isNotEmpty())
        <div x-data="{ cur: 0 }" class="relative w-full bg-gray-900 overflow-hidden" style="max-height:320px">
            <template x-for="(path, i) in {{ $postImgs->map(fn($p) => image_url($p))->toJson() }}" :key="i">
                <img :src="path" x-show="cur === i"
                     class="w-full object-cover" style="max-height:320px">
            </template>
            @if ($postImgs->count() > 1)
                <button @click.prevent="cur = (cur - 1 + {{ $postImgs->count() }}) % {{ $postImgs->count() }}"
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-black/50 rounded-full flex items-center justify-center text-white hover:bg-black/70">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click.prevent="cur = (cur + 1) % {{ $postImgs->count() }}"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 bg-black/50 rounded-full flex items-center justify-center text-white hover:bg-black/70">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
                    @for ($i = 0; $i < $postImgs->count(); $i++)
                        <span @click.prevent="cur = {{ $i }}"
                              class="w-1.5 h-1.5 rounded-full cursor-pointer transition-colors"
                              :class="cur === {{ $i }} ? 'bg-white' : 'bg-white/40'"></span>
                    @endfor
                </div>
            @endif
        </div>
    @endif

    {{-- リンク商品カード --}}
    @if ($post->product)
        <a href="{{ route('products.show', $post->product) }}"
           class="mx-4 mb-3 border border-gray-700 rounded-lg flex items-center gap-3 p-3 bg-black hover:bg-gray-900 transition-colors block">
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
        <div class="flex flex-wrap gap-1 px-4 pb-2">
            @foreach ($post->tags as $tag)
                <span class="text-xs bg-gray-800 text-gray-400 rounded-full px-2 py-0.5">#{{ $tag->name }}</span>
            @endforeach
        </div>
    @endif

    {{-- アクションバー（いいね・コメント） --}}
    <div class="px-4 pb-3 border-t border-gray-800 pt-2 flex items-center gap-5">
        {{-- いいね --}}
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

        {{-- コメント --}}
        <button @click="showComments = !showComments"
                class="flex items-center gap-1.5 text-sm transition-colors"
                :class="showComments ? 'text-[#0095f6]' : 'text-gray-500 hover:text-[#0095f6]'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span x-text="comments.length"></span>
        </button>

        {{-- 翻訳ボタン --}}
        <button @click="translate()"
                :disabled="translating"
                class="ml-auto flex items-center gap-1 text-xs transition-colors disabled:opacity-50"
                :class="showTranslated ? 'text-[#0095f6]' : 'text-gray-500 hover:text-[#0095f6]'">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
            </svg>
            <span x-show="!translating && !showTranslated">日本語に翻訳</span>
            <span x-show="translating" style="display:none">翻訳中...</span>
            <span x-show="!translating && showTranslated" style="display:none">原文を表示</span>
        </button>

        {{-- 詳細ページへ --}}
        <a href="{{ route('posts.show', $post) }}"
           class="text-xs text-gray-600 hover:text-gray-400 transition-colors">
            詳細を見る
        </a>
    </div>

    {{-- コメントセクション --}}
    <div x-show="showComments"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="border-t border-gray-800 px-4 pb-4">

        {{-- コメント一覧 --}}
        <div class="mt-3 space-y-3 max-h-64 overflow-y-auto">
            <template x-if="comments.length === 0">
                <p class="text-xs text-gray-600 text-center py-2">まだコメントがありません</p>
            </template>
            <template x-for="comment in comments" :key="comment.id">
                <div class="flex items-start gap-2">
                    <div class="w-7 h-7 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-xs shrink-0"
                         x-text="comment.user_name.charAt(0)"></div>
                    <div class="flex-1 min-w-0">
                        <span class="text-xs font-semibold text-gray-300" x-text="comment.user_name"></span>
                        <p class="text-xs text-gray-400 mt-0.5 break-words" x-text="comment.body"></p>
                    </div>
                    <button x-show="comment.user_id === authId"
                            @click="deleteComment(comment.id)"
                            class="text-gray-600 hover:text-red-400 transition-colors shrink-0 mt-0.5 p-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- コメント入力 --}}
        <div class="mt-3 flex items-center gap-2 pt-2 border-t border-gray-800/50">
            <div class="w-7 h-7 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-xs shrink-0">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
            </div>
            <input x-model="newComment"
                   @keydown.enter.prevent="submitComment"
                   type="text"
                   placeholder="コメントを追加..."
                   class="flex-1 bg-transparent border-b border-gray-700 text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:border-gray-500 py-1 transition-colors">
            <button @click="submitComment"
                    x-show="newComment.trim().length > 0"
                    x-transition
                    class="text-xs text-[#0095f6] font-semibold hover:text-[#1aa3ff] transition-colors">
                送信
            </button>
        </div>
    </div>
</article>
