@props(['href', 'icon', 'label'])

@php
    $isActive = request()->is(trim($href, '/') . '*');
@endphp

<a href="{{ $href }}" 
   class="flex items-center px-4 py-3.5 rounded-lg transition-colors duration-200 group relative mb-1
          {{ $isActive 
             ? 'bg-purple-600 text-white shadow-sm' 
             : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700' }}"
   :class="sidebarCollapsed ? 'justify-center px-0' : 'gap-4'">
    
    <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
        <i data-feather="{{ $icon }}" class="w-5 h-5"></i>
    </div>
    
    <span x-show="!sidebarCollapsed" 
          class="font-medium text-sm whitespace-nowrap">
        {{ $label }}
    </span>
</a>
