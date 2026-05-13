<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">ユーザープロフィール</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- プロフィールカード --}}
            <div class="bg-[#111111] border border-gray-800 rounded-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-xl shrink-0">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-lg">{{ $user->name }}</p>
                        <div class="flex gap-4 mt-1 text-sm text-gray-400">
                            <span><span class="text-white font-semibold">{{ $followersCount }}</span> フォロワー</span>
                            <span><span class="text-white font-semibold">{{ $followingCount }}</span> フォロー中</span>
                        </div>
                    </div>

                    @if (auth()->id() !== $user->id)
                        @if ($isFollowing)
                            <form method="POST" action="{{ route('users.unfollow', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-5 py-2 rounded-lg text-sm font-medium bg-gray-700 text-white hover:bg-gray-600 transition-colors border border-gray-600">
                                    フォロー中
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('users.follow', $user) }}">
                                @csrf
                                <button type="submit"
                                        class="px-5 py-2 rounded-lg text-sm font-medium bg-[#0095f6] text-white hover:bg-[#1aa3ff] transition-colors">
                                    フォロー
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            {{-- 投稿一覧 --}}
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
