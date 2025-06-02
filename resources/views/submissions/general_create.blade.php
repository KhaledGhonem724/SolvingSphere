<x-temp-general-layout :title="$title ?? 'Submit code'">

<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <form method="POST" action="{{ route('submissions.general.store') }}">
        @csrf
        <!-- Problem Handle -->
        <div class="mb-4">
            <x-input name="problem_handle" label="Enter Problem handle:" />
        </div>
        <!-- Code -->
        <div class="mb-4">
            <x-textarea name="code" label="Code" />
        </div>

        <!-- Language -->
        <div class="mb-4">

            <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
            <select name="language" id="language" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                <option value="">-- Select Language --</option>
                <option value="cpp" {{ old('language') == 'cpp' ? 'selected' : '' }}>C++</option>
                <option value="java" {{ old('language') == 'java' ? 'selected' : '' }}>Java</option>
                <option value="python" {{ old('language') == 'python' ? 'selected' : '' }}>Python</option>
            </select>
            @error('language')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit -->
        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Submit Solution
            </button>
        </div>
    </form>

</div>

  
</x-temp-general-layout>