<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-[#111111] border border-gray-700 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0095f6] focus:ring-offset-2 focus:ring-offset-black disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
