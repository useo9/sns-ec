@auth
<nav class="fixed bottom-0 left-0 right-0 bg-[#111111] border-t border-gray-800 sm:hidden z-50">
    <div class="flex items-center justify-around h-14 px-2">

        {{-- ホーム --}}
        @php $homeActive = request()->routeIs('posts.index'); @endphp
        <a href="{{ route('posts.index') }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 transition-colors
                  {{ $homeActive ? 'text-white' : 'text-gray-500' }}">
            <svg class="w-6 h-6" fill="{{ $homeActive ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] font-medium leading-none">ホーム</span>
        </a>

        {{-- 検索（商品一覧） --}}
        @php $searchActive = request()->routeIs('products.*'); @endphp
        <a href="{{ route('products.index') }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 transition-colors
                  {{ $searchActive ? 'text-white' : 'text-gray-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
            </svg>
            <span class="text-[10px] font-medium leading-none">検索</span>
        </a>

        {{-- 投稿 --}}
        @php $postActive = request()->routeIs('posts.create'); @endphp
        <a href="{{ route('posts.create') }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 transition-colors">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center
                        {{ $postActive ? 'bg-[#1aa3ff]' : 'bg-[#0095f6]' }} transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
            </div>
        </a>

        {{-- カート --}}
        @php $cartActive = request()->routeIs('carts.index'); @endphp
        <a href="{{ route('carts.index') }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 transition-colors
                  {{ $cartActive ? 'text-white' : 'text-gray-500' }}">
            <svg class="w-6 h-6" fill="{{ $cartActive ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-[10px] font-medium leading-none">カート</span>
        </a>

        {{-- マイページ --}}
        @php $profileActive = request()->routeIs('mypage'); @endphp
        <a href="{{ route('mypage') }}"
           class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 transition-colors
                  {{ $profileActive ? 'text-white' : 'text-gray-500' }}">
            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                        {{ $profileActive ? 'bg-[#0095f6] text-white' : 'bg-gray-700 text-gray-300' }}">
                {{ mb_substr(Auth::user()->name, 0, 1) }}
            </div>
            <span class="text-[10px] font-medium leading-none">マイページ</span>
        </a>

    </div>
    <!-- iPhone ホームバー用の余白 -->
    <div class="h-safe-area-inset-bottom bg-[#111111]"></div>
</nav>
@endauth
