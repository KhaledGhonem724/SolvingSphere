<x-temp-general-layout :title="$problem->title ?? 'Problem Page'">
<article class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8 space-y-6 mb-4">
        <!-- Blog Title -->
        <h1 class="text-4xl font-bold text-gray-900 leading-tight">
            Submission for Problem : {{ $submission->problem->title  }}
        </h1>
        <h1 class="text-xl font-normal text-gray-900 leading-tight">
            Problem handle: 
            <strong>
                {{ $submission->problem->problem_handle  }}
            </strong>
        </h1>
        <!-- Author and Meta -->
        <div class="flex items-center text-sm text-gray-500 space-x-4">
            <span>
                <p class="text-base ">
                    ID : 
                    <span class="text-base font-medium">
                        {{ $submission->id ?? 'problem_handle ???' }}
                    </span>
                </p>
            </span>    
            
            <span>•</span>
            <span>
            <a href="{{ $submission->original_link }}" 
                class="text-blue-600 underline" 
                target="_blank">
                Original Submission
            </a>

            </span>
            <span>•</span>

            <span>
                Language: <span class="font-semibold text-gray-700">
                    {{ $submission->language }}
                </span>
            </span>
        </div>

        <!-- Submissions Content -->
        <div class="prose max-w-none prose-lg prose-blue">
            <div class="mb-4">
                <strong>Final Result:</strong> {{ $submission->result }}
                <br>
                <strong>Online Judge Response:</strong> {{ $submission->oj_response }}
            </div>
            <div class="mb-4">
                <strong>Code:</strong> 
                <br>
                {!! $submission->code !!}
            </div>
        </div>


        <form method="POST" action="{{ route('submissions.ai_hint', $submission->id) }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Generate AI Hint
            </button>
        </form>



    </article>











    @if ($submission->ai_response)

    <article class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8 space-y-6">

        <!-- Ai Hint Content -->
        <div class="prose max-w-none prose-lg prose-blue">
            <div class="mb-4">
                <strong>AI Hint:</strong> 
                <br>
                {!! $submission->ai_response !!}
            </div>
        </div>



    </article>
    @endif
    


    

    
</x-temp-general-layout>
