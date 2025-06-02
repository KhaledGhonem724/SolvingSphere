<x-temp-general-layout :title="$title ?? 'Submissions'">
    <!-- resources/views/blogs/index.blade.php -->

    <x-listing title="All Submissions">
        <x-slot:creation>
            <p>Submit your solution!! </p>
            <x-button href="{{ route('submissions.general.create') }}" color="secondary">
                Submit Solution
            </x-button>
        </x-slot:creation>
        
        <x-slot:filters>
            <x-submission.filters />
        </x-slot:filters>

        <x-slot:items>
            @forelse ($submissions as $submission)
                <x-submission.item :submission="$submission" />                
            @empty
                <p>No Submissions yet.</p>
            @endforelse
        </x-slot:items>

    </x-listing>

</x-temp-general-layout>
