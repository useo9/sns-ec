<nav x-data="{ open: false }" class="bg-[#111111] border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14 sm:h-16">

            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ Auth::check() ? route('posts.index') : route('login') }}" class="shrink-0 flex items-center">
                    <x-application-logo class="block h-8 w-auto sm:h-9 fill-current text-white" />
                </a>

                {{-- Desktop nav links (auth) --}}
                @auth
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.index')">
                        ホーム
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        商品
                    </x-nav-link>
                    <x-nav-link :href="route('posts.create')" :active="request()->routeIs('posts.create')">
                        投稿
                    </x-nav-link>
                    <x-nav-link :href="route('carts.index')" :active="request()->routeIs('carts.*')">
                        カート
                    </x-nav-link>
                    <x-nav-link :href="route('mypage')" :active="request()->routeIs('mypage')">
                        マイページ
                    </x-nav-link>
                </div>
                @endauth
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-3">
                @auth
                    {{-- Desktop dropdown --}}
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-[#111111] hover:text-white focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Mobile: ログアウトボタンのみ（ハンバーガー） --}}
                    <div class="sm:hidden">
                        <button @click="open = ! open"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-300 hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @else
                    {{-- Guest links --}}
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-300 hover:text-white transition-colors">
                        ログイン
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="text-sm bg-[#0095f6] hover:bg-[#1aa3ff] text-white px-4 py-1.5 rounded-md transition-colors">
                            新規登録
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- Mobile dropdown (auth only: logout) --}}
    @auth
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-800">
        <div class="px-4 py-3 flex items-center gap-3 border-b border-gray-800">
            <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-300 shrink-0">
                {{ mb_substr(Auth::user()->name, 0, 1) }}
            </div>
            <div>
                <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <div class="py-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-4 py-2.5 text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    ログアウト
                </button>
            </form>
        </div>
    </div>
    @endauth
</nav>
