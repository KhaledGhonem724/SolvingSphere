<form method="GET" action="{{ route('submissions.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
    <div class="w-full md:w-1/3">
        <x-input 
            name="problem_title" 
            label="Problem Name" 
            :value="request('problem_title')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            input-class=""
        />
    </div>

    <div class="w-full md:w-1/3">
        <x-input 
            name="user_handle" 
            label="User Handle" 
            :value="request('user_handle')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            input-class=""
        />
    </div>

    <div class="w-full md:w-1/4">
        <x-select 
            name="result" 
            label="Status" 
            :options="[
                '' => 'All', 
                'solved' => 'solved', 
                'attempted' => 'attempted', 
            ]" 
            :value="request('result')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            select-class=""
        />
    </div>

    <div class="w-full md:w-1/4">
        <x-select 
            name="language" 
            label="Programming Language" 
            :options="[
                '' => 'All', 
                'cpp' => 'cpp', 
                'java' => 'java', 
                'python' => 'python'
            ]" 
            :value="request('language')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            select-class=""
        />
    </div>

    <div class="w-full sm:w-1/2 md:w-1/5 flex items-center gap-2">
        <input
            type="checkbox"
            id="my_submissions_only"
            name="my_submissions_only"
            value="1"
            {{ request()->boolean('my_submissions_only') ? 'checked' : '' }}
            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
        />
        <label for="my_submissions_only" class="text-gray-700 font-semibold select-none cursor-pointer">
            My Submissions Only
        </label>
    </div>
    
    <div class=" w-full flex gap-2 block">
        <x-button>Filter</x-button>
        <x-button href="{{ route('submissions.index') }}" color="secondary">Reset</x-button>
    </div>

</form>