@props(['title', 'value', 'icon', 'color' => 'purple'])

@php
    $colors = [
        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100', 'icon_bg' => 'bg-white'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'icon_bg' => 'bg-white'],
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'icon_bg' => 'bg-white'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'icon_bg' => 'bg-white'],
        'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-100', 'icon_bg' => 'bg-white'],
    ];
    $c = $colors[$color] ?? $colors['purple'];
@endphp

<div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-3xl p-6 transition-all duration-300 hover:shadow-xl hover:shadow-{{ $color }}-100 group">
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <p class="text-[10px] font-black {{ $c['text'] }} uppercase tracking-widest opacity-70">{{ $title }}</p>
            <h3 class="text-3xl font-black text-gray-800 tracking-tight transition-transform group-hover:scale-105 origin-left">
                {{ $value }}
            </h3>
        </div>
        <div class="{{ $c['icon_bg'] }} {{ $c['text'] }} w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg shadow-{{ $color }}/10">
            <i data-feather="{{ $icon }}" class="w-6 h-6 transition-transform group-hover:rotate-12"></i>
        </div>
    </div>
</div>
