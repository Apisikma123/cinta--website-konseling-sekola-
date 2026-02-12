<div class="bg-white rounded-lg border border-gray-200 p-6 card-hover">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">{{ $label }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
            @if(isset($subtitle))
                <p class="text-xs text-gray-500 mt-2">{{ $subtitle }}</p>
            @endif
        </div>
        @if(isset($icon))
            <div class="p-3 rounded-lg bg-purple-50">
                <i data-feather="{{ $icon }}" class="w-6 h-6 text-purple-600"></i>
            </div>
        @endif
    </div>
</div>
