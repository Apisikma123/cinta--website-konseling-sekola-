<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-6 py-2 rounded-lg font-medium text-base transition-colors duration-200 btn-hover']) }}
        :disabled="$disabled ?? false"
        :class="($disabled ?? false) ? 'opacity-50 cursor-not-allowed' : ''">
    @if(isset($icon))
        <i data-feather="{{ $icon }}" class="w-5 h-5"></i>
    @endif
    {{ $slot }}
</button>
