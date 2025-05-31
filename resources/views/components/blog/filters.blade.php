<form method="GET" action="{{ route('blogs.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
    <div class="w-full md:w-1/3">
        <x-input 
            name="title" 
            label="Title" 
            :value="request('title')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            input-class=""
        />
    </div>

    <div class="w-full md:w-1/4">
        <x-select 
            name="blog_type" 
            label="Type" 
            :options="[
                '' => 'All', 
                'discussion' => 'discussion', 
                'question' => 'question', 
                'explain' => 'explain'
            ]" 
            :value="request('blog_type')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            select-class=""
        />
    </div>

    <div class="w-full md:w-1/4">
        <x-input 
            name="owner_id" 
            label="Owner ID" 
            :value="request('owner_id')" 
            class="block w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            label-class="block mb-1 font-semibold text-gray-700"
            input-class=""
        />
    </div>

    <div class=" w-full flex gap-2 block">
        <x-button>Filter</x-button>
        <x-button href="{{ route('blogs.index') }}" color="secondary">Reset</x-button>
    </div>

</form>