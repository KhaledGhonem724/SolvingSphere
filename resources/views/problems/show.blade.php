<x-temp-general-layout :title="$problem->title ?? 'Problem Page'">

    <div class="mb-4">
        <h1 class="text-2xl font-bold">{{ $problem->title ?? 'Problem Name ???' }}</h1>
        <hr>
        <a href="{{ $problem->link }}" class="text-blue-600 underline" target="_blank">Original Problem</a>
    </div>

    <div class="mb-2">
        <strong>Website:</strong> {{ $problem->website }}
    </div>

    <!-- Blog Tags -->
    @if ($problem->tags->isNotEmpty())
        <div class="mt-2 mb-2 flex flex-wrap gap-2">
            @foreach ($problem->tags as $tag)
                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $tag->name }}
                </span>
            @endforeach
        </div>
    @endif

    <div class="mb-4">
        <strong>Time Limit:</strong> {{ $problem->timelimit }}
        <br>
        <strong>Memory Limit:</strong> {{ $problem->memorylimit }}
    </div>
    {!! $problem->statement !!}
    <!--
    <strong>Test Cases:</strong>
    {!! $problem->testcases !!}
    <strong>Notes:</strong>
    {!! $problem->notes !!}
    -->
    <hr><hr><hr><hr>
    
</x-temp-general-layout>
