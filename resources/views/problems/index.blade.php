<x-temp-listing-layout :title="'All Problems'" :pageTitle="'Problems'" >

    <x-slot name="filters">
        <form>
            <input type="text" name="search" placeholder="Search problems..." class="border px-2 py-1" />
        </form>
    </x-slot>
<!--
    <x-slot name="filters">
        <form>
            <input type="text" name="search" placeholder="Search problems..." class="border px-2 py-1" />
        </form>
    </x-slot>
-->
    
    <x-slot name="items">
        @foreach ($problems as $problem)
            <x-listing-item
            :title="$problem['title']"
            :description="$problem['description'] ?? ''"
            :itemLink="route('problems.show', $problem['problem_handle'])"
            />
        @endforeach
    </x-slot>

</x-temp-listing-layout>
