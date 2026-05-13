<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}"
               class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">商品を出品する</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
                  x-data="{
                      previews: [],
                      handleFiles(event) {
                          const files = Array.from(event.target.files);
                          const remaining = 5 - this.previews.length;
                          files.slice(0, remaining).forEach(file => {
                              const reader = new FileReader();
                              reader.onload = e => this.previews.push({ url: e.target.result, name: file.name });
                              reader.readAsDataURL(file);
                          });
                      },
                      removePreview(index) {
                          this.previews.splice(index, 1);
                      }
                  }">
                @csrf

                {{-- 画像アップロード --}}
                <div class="bg-[#111111] border border-gray-800 rounded-lg p-6 mb-4">
                    <h3 class="text-sm font-semibold text-white mb-4">商品画像 <span class="text-gray-500 font-normal">（最大5枚）</span></h3>

                    {{-- プレビュー --}}
                    <div class="grid grid-cols-4 gap-2 mb-3" x-show="previews.length > 0">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative aspect-square">
                                <img :src="preview.url" class="w-full h-full object-cover rounded-lg">
                                <button type="button" @click="removePreview(index)"
                                        class="absolute top-1 right-1 w-5 h-5 bg-black/70 rounded-full flex items-center justify-center text-white hover:bg-red-500 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <span x-show="index === 0"
                                      class="absolute bottom-1 left-1 text-[10px] bg-[#0095f6] text-white px-1.5 py-0.5 rounded">
                                    メイン
                                </span>
                            </div>
                        </template>

                        {{-- 追加ボタン --}}
                        <label x-show="previews.length < 5"
                               class="aspect-square border-2 border-dashed border-gray-700 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-gray-500 transition-colors">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden" @change="handleFiles">
                        </label>
                    </div>

                    {{-- 初期アップロードエリア --}}
                    <label x-show="previews.length === 0"
                           class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-700 rounded-lg cursor-pointer hover:border-gray-500 transition-colors">
                        <svg class="w-10 h-10 text-gray-600 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-gray-500">クリックして画像を選択</p>
                        <p class="text-xs text-gray-600 mt-1">PNG, JPG, WEBP（各5MB以内・最大5枚）</p>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden" @change="handleFiles">
                    </label>

                    @error('images.*')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 基本情報 --}}
                <div class="bg-[#111111] border border-gray-800 rounded-lg p-6 mb-4 space-y-4">
                    <h3 class="text-sm font-semibold text-white">基本情報</h3>

                    <div>
                        <x-input-label for="title" value="商品名 *" class="text-gray-400 text-xs" />
                        <x-text-input id="title" name="title" type="text"
                                      class="mt-1 block w-full bg-gray-900 border-gray-700 text-white"
                                      value="{{ old('title') }}" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="description" value="商品説明" class="text-gray-400 text-xs" />
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 resize-none"
                                  placeholder="商品の詳細、素材、使用状況などを記入してください">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="price" value="販売価格（円）*" class="text-gray-400 text-xs" />
                        <x-text-input id="price" name="price" type="number" min="1"
                                      class="mt-1 block w-full bg-gray-900 border-gray-700 text-white"
                                      value="{{ old('price') }}" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-1" />
                    </div>
                </div>

                {{-- 商品詳細 --}}
                <div class="bg-[#111111] border border-gray-800 rounded-lg p-6 mb-4 space-y-4">
                    <h3 class="text-sm font-semibold text-white">商品詳細</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="brand" value="ブランド" class="text-gray-400 text-xs" />
                            <x-text-input id="brand" name="brand" type="text"
                                          class="mt-1 block w-full bg-gray-900 border-gray-700 text-white"
                                          value="{{ old('brand') }}" />
                        </div>
                        <div>
                            <x-input-label for="size" value="サイズ" class="text-gray-400 text-xs" />
                            <x-text-input id="size" name="size" type="text"
                                          class="mt-1 block w-full bg-gray-900 border-gray-700 text-white"
                                          value="{{ old('size') }}" placeholder="S / M / L / 24cm" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="category" value="カテゴリ" class="text-gray-400 text-xs" />
                        <select id="category" name="category"
                                class="mt-1 block w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-gray-500">
                            <option value="">選択してください</option>
                            @foreach (['トップス', 'ボトムス', 'アウター', 'シューズ', 'バッグ', 'アクセサリー', 'その他'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="condition" value="商品の状態 *" class="text-gray-400 text-xs" />
                        <select id="condition" name="condition" required
                                class="mt-1 block w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-gray-500">
                            @foreach ([1 => '新品・未使用', 2 => '未使用に近い', 3 => '目立った傷や汚れなし', 4 => 'やや傷や汚れあり', 5 => '傷や汚れあり', 6 => '全体的に状態が悪い'] as $val => $label)
                                <option value="{{ $val }}" {{ old('condition', 3) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('condition')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="status" value="出品状態 *" class="text-gray-400 text-xs" />
                        <select id="status" name="status" required
                                class="mt-1 block w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-1 focus:ring-gray-500">
                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>出品中</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>下書き</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="tags" value="タグ（カンマ区切り）" class="text-gray-400 text-xs" />
                        <x-text-input id="tags" name="tags" type="text"
                                      class="mt-1 block w-full bg-gray-900 border-gray-700 text-white"
                                      value="{{ old('tags') }}" placeholder="Nike, スニーカー, 白" />
                        <p class="text-xs text-gray-600 mt-1">例: Nike, スニーカー, 白</p>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#0095f6] hover:bg-[#1aa3ff] text-white font-bold py-3 px-4 rounded-lg transition-colors">
                    出品する
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
