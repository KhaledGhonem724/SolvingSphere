<x-temp-general-layout :title="$problem->title ?? 'Problem Page'">

    <div class="mb-4">
        <h1 class="text-2xl font-bold">{{ $problem->title ?? 'Problem Name ???' }}</h1>
        <hr>
        <a href="{{ $problem->link }}" class="text-blue-600 underline" target="_blank">Original Problem</a>
    </div>

    <div class="mb-4">
        <strong>Website:</strong> {{ $problem->website }}
    </div>

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
