@php
    $variant = $variant ?? 'primary';
    $base = 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-sm transition transform hover:scale-105';
    $variants = [
        'primary' => $base . ' bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow',
        'outline' => $base . ' border border-purple-200 text-purple-700 bg-white hover:bg-purple-50',
        'ghost' => $base . ' text-gray-700 bg-transparent hover:bg-gray-100',
    ];
    $classes = $variants[$variant] ?? $variants['primary'];
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>