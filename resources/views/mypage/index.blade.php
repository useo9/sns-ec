<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">マイページ</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-900/30 border border-green-700 text-green-400 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- プロフィールカード --}}
            <div class="bg-[#111111] border border-gray-800 rounded-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-[#0095f6] flex items-center justify-center text-white font-bold text-2xl shrink-0">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-lg">{{ $user->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-700 text-white hover:bg-gray-600 transition-colors border border-gray-600">
                        編集
                    </a>
                </div>

                {{-- 統計 --}}
                <div class="flex gap-6 mt-5 pt-5 border-t border-gray-800">
                    <div class="text-center">
                        <p class="text-white font-bold text-xl">{{ $postsCount }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">投稿</p>
                    </div>
                    <div class="text-center">
                        <p class="text-white font-bold text-xl">{{ $followersCount }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">フォロワー</p>
                    </div>
                    <div class="text-center">
                        <p class="text-white font-bold text-xl">{{ $followingCount }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">フォロー中</p>
                    </div>
                </div>
            </div>

            {{-- 投稿一覧 --}}
            <h3 class="text-sm font-semibold text-gray-400 px-1">自分の投稿</h3>

            @forelse ($posts as $post)
                @include('posts._card')
            @empty
                <div class="text-center text-gray-600 py-16">
                    まだ投稿がありません
                </div>
            @endforelse

            @if ($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
