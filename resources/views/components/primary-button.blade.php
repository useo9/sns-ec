<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#0095f6] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1aa3ff] focus:bg-[#1aa3ff] active:bg-[#0077cc] focus:outline-none focus:ring-2 focus:ring-[#0095f6] focus:ring-offset-2 focus:ring-offset-black transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
