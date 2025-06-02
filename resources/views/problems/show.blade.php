<x-temp-general-layout :title="$problem->title ?? 'Problem Page'">
    <article class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8 space-y-6">
        <!-- Blog Title -->
        <h1 class="text-4xl font-bold text-gray-900 leading-tight">
            {{ $problem->title ?? 'Problem Name ???' }}
        </h1>

        <!-- Author and Meta -->
        <div class="flex items-center text-sm text-gray-500 space-x-4">
            <span>
                <p class="text-base ">
                    Handle : 
                    <span class="text-base font-medium">
                        {{ $problem->problem_handle ?? 'problem_handle ???' }}
                    </span>
                </p>
            </span>    
            
            <span>•</span>
            <span>
                <a href="{{ $problem->link }}" class="text-blue-600 underline" target="_blank">
                    Original Problem
                </a>
            </span>
            <span>•</span>

            <span>
                Website: <span class="font-semibold text-gray-700">
                    {{ $problem->website }}
                </span>
            </span>
        </div>


        <div class="mb-4">
            <strong>Time Limit:</strong> {{ $problem->timelimit }}
            <br>
            <strong>Memory Limit:</strong> {{ $problem->memorylimit }}
        </div>

        <!-- Problem Tags -->
        @if ($problem->tags->isNotEmpty())
            <div class="mt-2 mb-2 flex flex-wrap gap-2">
                @foreach ($problem->tags as $tag)
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Problem Content -->
        <div class="prose max-w-none prose-lg prose-blue">
            {!! $problem->statement !!}
            <strong>Test Cases:</strong>
            {!! $problem->testcases !!}
            <strong>Notes:</strong>
            {!! $problem->notes !!}
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
            @auth
                <a href="{{ route('submissions.create', $problem->problem_handle) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-base font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                    <span>🛠️</span>
                    <span>Solve Now</span>
                </a>

                <a href="{{ route('problem.submissions', $problem->problem_handle) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 text-gray-700 text-base font-medium rounded-lg hover:bg-gray-100 transition">
                    <span>📄</span>
                    <span>My Submissions</span>
                </a>
            @endauth
        </div>


    </article>
    
</x-temp-general-layout>
