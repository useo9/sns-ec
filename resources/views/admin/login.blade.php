<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理者ログイン | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-black min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white">管理画面</h1>
            <p class="text-gray-500 text-sm mt-1">管理者アカウントでログイン</p>
        </div>

        <div class="bg-[#111111] border border-gray-800 rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">
                        メールアドレス
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border-gray-700 bg-[#111111] text-white rounded-lg shadow-sm text-sm
                                  focus:ring-[#0095f6] focus:border-[#0095f6]
                                  @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">
                        パスワード
                    </label>
                    <input id="password" type="password" name="password" required
                           class="w-full border-gray-700 bg-[#111111] text-white rounded-lg shadow-sm text-sm
                                  focus:ring-[#0095f6] focus:border-[#0095f6]">
                </div>

                <button type="submit"
                        class="w-full bg-[#0095f6] hover:bg-[#1aa3ff] text-white font-medium py-2.5 rounded-lg text-sm transition-colors">
                    ログイン
                </button>
            </form>
        </div>
    </div>
</body>
</html>
