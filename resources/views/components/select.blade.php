@props(['name', 'label', 'options' => [], 'value' => ''])

<div class="mb-4">
    <label for="{{ $name }}" class="block font-semibold mb-1">{{ $label }}</label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select w-full border rounded px-3 py-2']) }}
    >
        @foreach ($options as $option)
            <option value="{{ $option }}" @selected(old($name, $value) == $option)>{{ ucfirst($option) }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
