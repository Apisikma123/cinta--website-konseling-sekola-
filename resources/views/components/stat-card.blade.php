<x-card class="p-4" :title="$label">
    <div class="flex items-center space-x-3">
        <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-{{ $color ?? 'purple' }}-50">
            <i class="fas fa-{{ $icon ?? 'chart-bar' }} text-{{ $color ?? 'purple' }}-600"></i>
        </div>
        <div>
            <div class="text-lg font-bold text-gray-900">{{ $value }}</div>
            <div class="text-xs text-gray-500">{{ $label }}</div>
        </div>
    </div>
</x-card>