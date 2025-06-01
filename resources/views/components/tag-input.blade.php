@props([
    'name',
    'label',
    'value' => '', // comma-separated tags
    'whitelist' => [], // optional, array of allowed tags for autocomplete
])

<div class="mb-4">
    <label for="{{ $name }}" class="block font-semibold mb-1">{{ $label }}</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'tagify-input w-full border rounded px-3 py-2']) }}
        data-whitelist='@json($whitelist)'
        autocomplete="off"
    />

    @error($name)
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" />
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll('.tagify-input').forEach(el => {
                    const tagify = new Tagify(el, {
                        whitelist: JSON.parse(el.dataset.whitelist || '[]'),
                        enforceWhitelist: false,
                        dropdown: {
                            maxItems: 20,
                            classname: "tags-look",
                            enabled: 0,
                            closeOnSelect: false
                        }
                    });

                    // On change event: update the input value to a comma-separated string of tag values
                    tagify.on('change', () => {
                        const values = tagify.value.map(tag => tag.value);
                        el.value = values.join(',');
                    });
                });
            });
        </script>
    @endpush
@endonce
