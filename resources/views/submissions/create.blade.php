<x-temp-general-layout :title="$title ?? 'Create Problem Page'">
    
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <form method="POST" action="{{ route('submissions.store') }}">
        @csrf
        <!-- Code -->
        <div class="mb-4">
            <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
            <textarea name="code" id="code" rows="6" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">{{ old('code') }}</textarea>
            @error('code')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
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

        <!-- Owner ID -->
        <div class="mb-4">
            <label for="owner_id" class="block text-sm font-medium text-gray-700">Owner ID</label>
            <input type="text" name="owner_id" id="owner_id" value="{{ old('owner_id') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            @error('owner_id')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Problem ID -->
        <div class="mb-4">
            <label for="problem_id" class="block text-sm font-medium text-gray-700">Problem ID</label>
            <input type="text" name="problem_id" id="problem_id" value="{{ old('problem_id') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
            @error('problem_id')
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

    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
        <form method="POST" action="{{ route('problems.store') }}">
            @csrf

            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Enter URL:</label>
            <input
                type="url"
                id="problem-url"
                name="problem-url"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="https://example.com"
            >

            <button
                type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition"
            >
                Submit
            </button>
        </form>
    </div>
</x-temp-general-layout>