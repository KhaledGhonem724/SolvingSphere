<form method="GET" action="{{ route('problems.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
    
    <div class="w-full md:w-1/3">
        <x-input 
            name="title" 
            label="Problem Name" 
            :value="request('title')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            input-class=""
        />
    </div>

    <div class="w-full md:w-1/4">
        <x-select 
            name="website" 
            label="Online Judge" 
            :options="[
                '' => 'All', 
                'HackerEarth' => 'HackerEarth', 
                'McClure-Witting' => 'McClure-Witting', 
                'leetcode' => 'leetcode'
            ]" 
            :value="request('website')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            select-class=""
        />
    </div>
    @auth
        <div class="w-full md:w-1/4">
            <x-select 
                name="state" 
                label="Problem State" 
                :options="[
                    '' => 'All', 
                    'solved' => 'solved', 
                    'attempted' => 'attempted', 
                    'todo' => 'todo'
                ]" 
                :value="request('state')" 
                class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                label-class="block mb-1 font-semibold text-gray-700"
                select-class=""
            />
        </div>
    @endauth
    
        <div class="w-full md:w-2/5 mb-4">
        <label class="block mb-2 font-semibold text-gray-700">Tags</label>
        <div class="flex flex-wrap gap-3 max-h-40 overflow-y-auto border rounded border-gray-300 p-3 bg-white">
            @foreach ($allTags as $tag)
                <label class="inline-flex items-center space-x-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="tags[]"
                        value="{{ $tag->id }}"
                        @if(in_array($tag->id, request('tags', []))) checked @endif
                        class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                    />
                    <span class="capitalize text-gray-800 select-none">{{ $tag->name }}</span>
                </label>
            @endforeach
        </div>
        @error('tags')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="w-full sm:w-1/2 md:w-1/5 flex items-center gap-2">
        <input
            type="checkbox"
            id="match_all_tags"
            name="match_all_tags"
            value="1"
            {{ request()->boolean('match_all_tags') ? 'checked' : '' }}
            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
        />
        <label for="match_all_tags" class="text-gray-700 font-semibold select-none cursor-pointer">
            Match all selected tags
        </label>
    </div>
    
    <div class=" w-full flex gap-2 block">
        <x-button>Filter</x-button>
        <x-button href="{{ route('problems.index') }}" color="secondary">Reset</x-button>
    </div>

</form>