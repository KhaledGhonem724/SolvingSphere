@props([
    'submission',
    'badge' => ucfirst($submission->result),
    'title' => $submission->problem->title,
    'sub_title' => $submission->problem->problem_handle,
    'language' => $submission->language,
    'badgeClass' => match($submission->result) {
        'failed' => 'bg-red-100 text-red-700',
        'succeeded' => 'bg-green-100 text-green-700',
        default => 'bg-gray-100 text-gray-700',
    }
])
<x-listing-item 
    :title="$title" 
    :sub_title="$sub_title" 
    :href="route('submissions.show', $submission)" 
    :badge="$badge" 
    badgeClass="{{ $badgeClass }}">


    <x-slot:meta>
        Language : <strong> {{ $language }} </strong> • 
        By : <strong> {{ $submission->owner->name }} </strong> • 
        {{ $submission->created_at->diffForHumans() }}
    </x-slot:meta>
    
    
</x-listing-item>


