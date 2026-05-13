@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-700 bg-[#111111] text-white placeholder-gray-500 focus:border-[#0095f6] focus:ring-[#0095f6] rounded-md shadow-sm']) }}>
