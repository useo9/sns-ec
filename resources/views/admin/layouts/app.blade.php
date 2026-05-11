<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理画面 | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">

        {{-- サイドバー --}}
        <aside class="w-56 bg-gray-900 text-gray-300 flex flex-col shrink-0">
            <div class="px-6 py-5 border-b border-gray-700">
                <span class="text-white font-bold text-lg">管理画面</span>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    注文一覧
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    ユーザー一覧
                </a>
            </nav>
            <div class="px-3 py-4 border-t border-gray-700">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-400 hover:bg-gray-800 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        ログアウト
                    </button>
                </form>
            </div>
        </aside>

        {{-- メインコンテンツ --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-800">@yield('title')</h1>
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
            </header>
            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
