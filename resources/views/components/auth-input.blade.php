@props([
    'type' => 'text',
    'name',
    'label',
    'id' => null,
    'required' => false,
    'autocomplete' => null,
    'placeholder' => null,
    'showPasswordToggle' => false,
    'value' => null,
])

@php
    $fieldId = $id ?? $name;
    $hasError = $errors->has($name);
    $inputClasses = \Illuminate\Support\Str::of(
        'w-full rounded-xl border bg-gray-50/70 px-4 py-3.5 text-sm text-gray-800 placeholder-transparent transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 '
    )->append($hasError
        ? 'border-red-300 focus:border-red-400 focus:ring-red-100'
        : 'border-gray-200 focus:border-[#6a63e7] focus:ring-[#6a63e7]/10');
@endphp

<div class="space-y-1.5">
    <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
    </label>

    <div class="relative">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $fieldId }}"
            @if($value) value="{{ $value }}" @endif
            @if($required) required @endif
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            placeholder="{{ $placeholder ?? $label }}"
            {{ $attributes->merge(['class' => $inputClasses]) }}
            @if($showPasswordToggle) x-bind:type="showPassword ? 'text' : 'password'" @endif
        >

        @if($showPasswordToggle)
            <button type="button"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 transition-colors hover:text-[#6a63e7]"
                    aria-label="Toggle password visibility"
                    @click="showPassword = !showPassword">
                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                </svg>
            </button>
        @endif
    </div>

    @error($name)
        <p class="text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
