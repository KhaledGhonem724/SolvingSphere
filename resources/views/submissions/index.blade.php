<x-temp-listing-layout :title="'All Submissions'" :pageTitle="'Submissions'" >

    <x-slot name="filters">
        <form>
            <input type="text" name="search" placeholder="Search problems..." class="border px-2 py-1" />
        </form>
    </x-slot>
    
    <x-slot name="items">
        ha ha
        @foreach ($submissions as $submission)
            <x-listing-item
            :title="$submission['language']"
            :description="$submission['result'] ?? ''"
            :itemLink="route('submissions.show', $submission['original_link'])"
            />
        @endforeach
    </x-slot>

</x-temp-listing-layout>
