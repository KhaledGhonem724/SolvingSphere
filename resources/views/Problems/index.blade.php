<x-list-layout :title="'Problems List'" :pageTitle="'Problems'" >

    <x-slot name="filters">
        <form>
            <input type="text" name="search" placeholder="Search problems..." class="border px-2 py-1" />
        </form>
    </x-slot>

    <x-slot name="items">
        <ul>
            @foreach ($problems as $problem)
                <li class="border-b py-2">{{ $problem->title }}</li>
            @endforeach
        </ul>
    </x-slot>

</x-list-layout>
