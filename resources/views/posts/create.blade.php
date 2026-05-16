<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">投稿を作成</h2>
    
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#111111] shadow-sm rounded-lg overflow-hidden border border-gray-800">
                <form method="POST" action="{{ route('posts.store') }}"
                      enctype="multipart/form-data"
                      x-data="postForm()" class="p-6 space-y-5">
                    @csrf

                    {{-- 画像アップロード --}}
                    <div>
                        <x-input-label value="画像（任意・5MB以内）" />
                        <label class="mt-1 block cursor-pointer">
                            {{-- プレビュー表示エリア --}}
                            <div x-show="preview"
                                 class="relative w-full rounded-lg overflow-hidden bg-gray-900"
                                 style="display:none">
                                <img :src="preview" class="w-full max-h-72 object-cover">
                                <span class="absolute inset-0 flex items-center justify-center
                                             bg-black/50 opacity-0 hover:opacity-100 transition text-white text-sm font-medium">
                                    画像を変更
                                </span>
                            </div>

                            {{-- 未選択時のプレースホルダー --}}
                            <div x-show="!preview"
                                 class="mt-1 flex flex-col items-center justify-center gap-2
                                        w-full h-40 border-2 border-dashed border-gray-700
                                        rounded-lg hover:border-gray-500 transition text-gray-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm">クリックして画像を選択</span>
                            </div>

                            <input type="file" name="image" class="hidden"
                                   accept="image/jpeg,image/png,image/webp"
                                   @change="onFileChange($event)">
                        </label>
                        <x-input-error :messages="$errors->get('image')" class="mt-1" />
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
                                         focus:ring-[#0095f6] focus:border-[#0095f6] text-sm resize-none"
                                  placeholder="コーディネートや古着への想いを書いてみましょう">{{ old('body') }}</textarea>
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
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
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
                                      :value="old('tags')"
                                      placeholder="例：古着, ビンテージ, 90s" />
                        <x-input-error :messages="$errors->get('tags')" class="mt-1" />
                    </div>

                    {{-- 送信 --}}
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('posts.index') }}" class="text-sm text-gray-400 hover:underline">
                            キャンセル
                        </a>
                        <x-primary-button>投稿する</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function postForm() {
            return {
                preview: null,
                body: '{{ old('body', '') }}',
                get bodyLength() { return this.body.length; },
                onFileChange(e) {
                    const file = e.target.files[0];
                    if (file) this.preview = URL.createObjectURL(file);
                },
            };
        }
    </script>
</x-app-layout>
