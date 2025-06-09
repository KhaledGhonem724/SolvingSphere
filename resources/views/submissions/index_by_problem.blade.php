<x-temp-general-layout :title="$title ?? 'Problem Submissions'">
    <!-- resources/views/blogs/index.blade.php -->

    <x-listing title="Problem Submissions">
        <x-slot:creation>
            <p>want to view all submissions </p>
            <x-button href="{{ route('submissions.index') }}" color="secondary">
                All submissions
            </x-button>
        </x-slot:creation>
        <x-slot:items>
            @forelse ($submissions as $submission)
                <x-submission.item :submission="$submission" />                
            @empty
                <p>No Submissions yet.</p>
            @endforelse
        </x-slot:items>

    </x-listing>

</x-temp-general-layout>
