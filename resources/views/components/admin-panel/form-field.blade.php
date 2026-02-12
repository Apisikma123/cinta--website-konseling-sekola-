<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-900">
        {{ $label }}
        @if(isset($required) && $required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea id="{{ $name }}"
                  name="{{ $name }}"
                  {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base font-medium']) }}
                  rows="{{ $rows ?? 4 }}">{{ old($name, $value ?? '') }}</textarea>
    @elseif($type === 'select')
        <select id="{{ $name }}"
                name="{{ $name }}"
                {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base font-medium']) }}>
            <option value="">{{ $placeholder ?? 'Pilih...' }}</option>
            @foreach($options ?? [] as $optValue => $optLabel)
                <option value="{{ $optValue }}" {{ old($name, $value ?? '') == $optValue ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach
        </select>
    @else
        <input type="{{ $type ?? 'text' }}"
               id="{{ $name }}"
               name="{{ $name }}"
               value="{{ old($name, $value ?? '') }}"
               {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base font-medium']) }}>
    @endif

    @error($name)
        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
    @enderror

    @if(isset($hint))
        <p class="text-xs text-gray-500">{{ $hint }}</p>
    @endif
</div>
