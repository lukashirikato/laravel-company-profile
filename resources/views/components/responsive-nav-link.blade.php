@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-left text-base font-medium text-secondary bg-light-pink/30 focus:outline-none focus:text-indigo-800 focus:bg-light-pink/50 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-dark/70 hover:text-dark hover:bg-cream hover:border-light-pink/60 focus:outline-none focus:text-dark focus:bg-cream focus:border-light-pink/60 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
