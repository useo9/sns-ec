<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">タイムライン</h2>
            <a href="{{ route('posts.create') }}"
               class="bg-[#0095f6] hover:bg-[#1aa3ff] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                投稿する
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-900/30 border border-green-700 text-green-400 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

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
