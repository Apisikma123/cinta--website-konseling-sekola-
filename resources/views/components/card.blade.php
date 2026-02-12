@props(['title' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg p-4 border border-gray-100 shadow-sm hover:shadow-md transition-transform transform hover:-translate-y-1']) }}>
    @if($title)
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">{{ $title }}</h3>
            {{ $header ?? '' }}
        </div>
    @endif

    <div class="">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="mt-4 text-sm text-gray-500">{{ $footer }}</div>
    @endif
</div>