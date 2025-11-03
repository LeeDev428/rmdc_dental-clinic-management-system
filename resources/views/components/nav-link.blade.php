@props(['active' => false])

@php
$classes = $active ?? false
    ? 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-900 dark:text-white hover:text-gray-950 focus:outline-none transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 text-sm font-medium leading-5 text-gray-500 dark:text-gray-500 hover:text-gray-950 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>