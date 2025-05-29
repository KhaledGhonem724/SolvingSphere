@props(['name', 'label', 'value' => ''])

<div class="mb-4">
    <label for="{{ $name }}" class="block font-semibold mb-1">{{ $label }}</label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="5"
        {{ $attributes->merge(['class' => 'form-textarea w-full border rounded px-3 py-2']) }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
