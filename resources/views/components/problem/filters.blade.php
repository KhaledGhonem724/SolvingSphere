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
                'hacker-earth' => 'hacker-earth', 
                'codeforces' => 'codeforces', 
                'leetcode' => 'leetcode'
            ]" 
            :value="request('website')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            select-class=""
        />
    </div>

    <div class=" w-full flex gap-2 block">
        <x-button>Filter</x-button>
        <x-button href="{{ route('problems.index') }}" color="secondary">Reset</x-button>
    </div>

</form>