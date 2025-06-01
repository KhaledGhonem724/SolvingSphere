@props([
    'name',
    'label',
    'options' => [],
    'value' => [],
])

@php
    $selected = (array) old($name, $value ?? []);
@endphp

<div {{ $attributes->merge(['class' => 'mb-5']) }}>
    <label for="{{ $name }}" class="block mb-2 text-sm font-semibold text-gray-700">{{ $label }}</label>

    <select
        id="{{ $name }}"
        name="{{ $name }}[]"  
        multiple
        class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 text-gray-700
               shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 focus:ring-opacity-50
               transition duration-150 ease-in-out"
        aria-multiselectable="true"
    >
        @foreach ($options as $optionValue => $optionLabel)
            <option
                value="{{ $optionValue }}"
                @selected(in_array($optionValue, $selected))
                class="capitalize"
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
