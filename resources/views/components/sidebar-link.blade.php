@props(['active', 'open' => true, 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'])

@php
$classes = ($active ?? false)
? 'bg-orange-700 text-white flex items-center p-3 rounded-lg transition-all shadow-md'
: 'text-stone-400 hover:bg-stone-800 hover:text-white flex items-center p-3 rounded-lg transition-all';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>