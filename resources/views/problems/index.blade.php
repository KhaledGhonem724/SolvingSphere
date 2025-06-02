<x-temp-general-layout :title="$title ?? 'Problems'">
    <!-- resources/views/blogs/index.blade.php -->

    <x-listing title="All Problems">
        <x-slot:creation>
            <p>Can't find your problem? </p>
            <x-button href="{{ route('problems.create') }}" color="secondary">
                Scrape New problem
            </x-button>
        </x-slot:creation>

        <x-slot:filters>
            <x-problem.filters :allTags="$allTags" />
        </x-slot:filters>

        <x-slot:items>

            @forelse ($problems as $problem)
                <x-problem.item :problem="$problem" :userSubmissions="$userSubmissions ?? []" />
            @empty
                <p>No problems found.</p>
            @endforelse
        </x-slot:items>


    </x-listing>

</x-temp-general-layout>

