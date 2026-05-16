<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">投稿を編集</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#111111] shadow-sm rounded-lg overflow-hidden border border-gray-800">
                <form method="POST" action="{{ route('posts.update', $post) }}"
                      enctype="multipart/form-data"
                      x-data="postForm()" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- 画像（最大5枚） --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <x-input-label value="画像（最大5枚・各5MB以内）" />
                            <span class="text-xs text-gray-500" x-text="previews.length + ' / 5'"></span>
                        </div>
                        <p class="text-xs text-gray-600 mb-2">新しい画像を追加すると既存の画像はすべて置き換わります</p>

                        {{-- 既存画像（新画像未選択時のみ表示） --}}
                        @if ($post->postImages->isNotEmpty())
                            <div x-show="files.length === 0" class="grid grid-cols-5 gap-2 mb-2">
                                @foreach ($post->postImages as $img)
                                    <div class="relative aspect-square rounded-lg overflow-hidden bg-gray-800">
                                        <img src="{{ image_url($img->image_path) }}" class="w-full h-full object-cover">
                                        <span class="absolute bottom-0 left-0 right-0 text-center text-[9px] text-gray-400 bg-black/60 py-0.5">現在</span>
                                    </div>
                                @endforeach
                            </div>
                        @elseif ($post->image_path)
                            <div x-show="files.length === 0" class="grid grid-cols-5 gap-2 mb-2">
                                <div class="relative aspect-square rounded-lg overflow-hidden bg-gray-800">
                                    <img src="{{ image_url($post->image_path) }}" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 left-0 right-0 text-center text-[9px] text-gray-400 bg-black/60 py-0.5">現在</span>
                                </div>
                            </div>
                        @endif

                        {{-- 新規選択サムネイル --}}
                        <div class="grid grid-cols-5 gap-2 mb-2" x-show="previews.length > 0">
                            <template x-for="(src, i) in previews" :key="i">
                                <div class="relative aspect-square rounded-lg overflow-hidden bg-gray-800">
                                    <img :src="src" class="w-full h-full object-cover">
                                    <button type="button" @click="removeImage(i)"
                                            class="absolute top-0.5 right-0.5 w-5 h-5 bg-black/70 rounded-full flex items-center justify-center text-white hover:bg-red-600 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- 追加ボタン --}}
                        <label x-show="previews.length < 5"
                               class="flex flex-col items-center justify-center gap-2 w-full h-28
                                      border-2 border-dashed border-gray-700 rounded-lg
                                      hover:border-gray-500 transition cursor-pointer text-gray-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-sm" x-text="files.length > 0 ? '画像を追加' : '画像を変更'"></span>
                            <input type="file" name="images[]" class="hidden"
                                   accept="image/jpeg,image/png,image/webp"
                                   multiple
                                   @change="onFileChange($event)">
                        </label>
                        <x-input-error :messages="$errors->get('images')" class="mt-1" />
                        <x-input-error :messages="$errors->get('images.*')" class="mt-1" />
                    </div>

                    {{-- 本文 --}}
                    <div>
                        <div class="flex justify-between items-center">
                            <x-input-label for="body" value="本文" />
                            <span class="text-xs text-gray-500" x-text="bodyLength + ' / 1000'"></span>
                        </div>
                        <textarea id="body" name="body" rows="4" required maxlength="1000"
                                  x-model="body"
                                  class="mt-1 block w-full border-gray-700 bg-[#111111] text-white placeholder-gray-500 rounded-md shadow-sm
                                         focus:ring-[#0095f6] focus:border-[#0095f6] text-sm resize-none">{{ old('body', $post->body) }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-1" />
                    </div>

                    {{-- 商品リンク --}}
                    @if ($products->isNotEmpty())
                        <div>
                            <x-input-label for="product_id" value="商品をリンクする（任意）" />
                            <select id="product_id" name="product_id"
                                    class="mt-1 block w-full border-gray-700 bg-[#111111] text-white rounded-md shadow-sm
                                           focus:ring-[#0095f6] focus:border-[#0095f6] text-sm">
                                <option value="">選択しない</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ old('product_id', $post->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_id')" class="mt-1" />
                        </div>
                    @endif

                    {{-- タグ --}}
                    <div>
                        <x-input-label for="tags" value="タグ（任意・カンマ区切り）" />
                        <x-text-input id="tags" name="tags" type="text" class="mt-1 block w-full"
                                      :value="old('tags', $post->tags->pluck('name')->join(', '))"
                                      placeholder="例：古着, ビンテージ, 90s" />
                        <x-input-error :messages="$errors->get('tags')" class="mt-1" />
                    </div>

                    {{-- 送信 --}}
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('posts.show', $post) }}" class="text-sm text-gray-400 hover:underline">
                            キャンセル
                        </a>
                        <x-primary-button>更新する</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function postForm() {
            return {
                previews: [],
                files: [],
                body: '{{ old('body', addslashes($post->body)) }}',
                get bodyLength() { return this.body.length; },
                onFileChange(e) {
                    const selected = Array.from(e.target.files);
                    const remaining = 5 - this.previews.length;
                    const toAdd = selected.slice(0, remaining);
                    toAdd.forEach(file => {
                        this.files.push(file);
                        this.previews.push(URL.createObjectURL(file));
                    });
                    e.target.value = '';
                    this.syncFileInput();
                },
                removeImage(i) {
                    this.previews.splice(i, 1);
                    this.files.splice(i, 1);
                    this.syncFileInput();
                },
                syncFileInput() {
                    const dt = new DataTransfer();
                    this.files.forEach(f => dt.items.add(f));
                    document.querySelectorAll('input[name="images[]"]').forEach(input => {
                        input.files = dt.files;
                    });
                },
            };
        }
    </script>
</x-app-layout>
