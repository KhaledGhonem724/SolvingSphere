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

    <div class="mb-4">
        <strong>Statement:</strong>
        <div class="mt-2">{!! $problem->statement !!}</div>
    </div>

    <div class="mb-4">
        <strong>Test Cases:</strong>
        <div class="mt-2">{!! $problem->testcases !!}</div>
    </div>

    <div class="mb-4">
        <strong>Notes:</strong>
        <div class="mt-2">{!! $problem->notes !!}</div>
    </div>

</x-temp-general-layout>
