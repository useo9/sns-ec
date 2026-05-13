<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">購入確認</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="p-4 bg-red-900/30 border border-red-700 text-red-400 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- 注文内容 --}}
            <div class="bg-[#111111] border border-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h3 class="font-semibold text-white">注文内容</h3>
                </div>
                <ul class="divide-y divide-gray-800">
                    @foreach ($carts as $cart)
                        <li class="flex items-center gap-4 p-4">
                            @if ($cart->product->image_path)
                                <img src="{{ asset('storage/' . $cart->product->image_path) }}"
                                     alt="{{ $cart->product->title }}"
                                     class="w-16 h-16 object-cover rounded shrink-0">
                            @else
                                <div class="w-16 h-16 bg-gray-800 rounded flex items-center justify-center text-gray-500 text-xs shrink-0">
                                    No Image
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $cart->product->title }}</p>
                                @if ($cart->product->brand)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $cart->product->brand }}</p>
                                @endif
                            </div>
                            <p class="font-bold text-white shrink-0">¥{{ number_format($cart->product->price) }}</p>
                        </li>
                    @endforeach
                </ul>
                <div class="px-6 py-4 bg-black border-t border-gray-800 flex justify-between items-center">
                    <span class="font-semibold text-gray-300">合計（{{ $carts->count() }}点）</span>
                    <span class="text-xl font-bold text-white">¥{{ number_format($total) }}</span>
                </div>
            </div>

            {{-- 配送先フォーム --}}
            <div class="bg-[#111111] border border-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h3 class="font-semibold text-white">配送先</h3>
                </div>
                <form method="POST" action="{{ route('orders.store') }}" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="shipping_name" value="お名前" />
                        <x-text-input id="shipping_name" name="shipping_name" type="text"
                                      class="mt-1 block w-full" :value="old('shipping_name')" required />
                        <x-input-error :messages="$errors->get('shipping_name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="shipping_postal_code" value="郵便番号（例：123-4567）" />
                        <x-text-input id="shipping_postal_code" name="shipping_postal_code" type="text"
                                      class="mt-1 block w-48" :value="old('shipping_postal_code')"
                                      placeholder="123-4567" required />
                        <x-input-error :messages="$errors->get('shipping_postal_code')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="shipping_prefecture" value="都道府県" />
                        <select id="shipping_prefecture" name="shipping_prefecture"
                                class="mt-1 block w-40 border-gray-700 bg-[#111111] text-white rounded-md shadow-sm focus:ring-[#0095f6] focus:border-[#0095f6] text-sm"
                                required>
                            <option value="">選択</option>
                            @foreach (['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'] as $pref)
                                <option value="{{ $pref }}" {{ old('shipping_prefecture') === $pref ? 'selected' : '' }}>
                                    {{ $pref }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('shipping_prefecture')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="shipping_address" value="住所（市区町村・番地）" />
                        <x-text-input id="shipping_address" name="shipping_address" type="text"
                                      class="mt-1 block w-full" :value="old('shipping_address')" required />
                        <x-input-error :messages="$errors->get('shipping_address')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="shipping_building" value="建物名・部屋番号（任意）" />
                        <x-text-input id="shipping_building" name="shipping_building" type="text"
                                      class="mt-1 block w-full" :value="old('shipping_building')" />
                        <x-input-error :messages="$errors->get('shipping_building')" class="mt-1" />
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-3 text-base">
                            注文を確定する
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div>
                <a href="{{ route('carts.index') }}" class="text-sm text-gray-400 hover:underline">← カートへ戻る</a>
            </div>
        </div>
    </div>
</x-app-layout>
