<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'My App' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body class="flex flex-col min-h-screen">

    {{-- Navbar Slot, or default navbar --}}
    {{ $navbar ?? view('components.navbar') }}

    {{-- Main Content --}}
    <main class="flex-1 p-4">
        {{ $slot }}
    </main>

    {{-- Footer Slot, or default footer --}}
    {{ $footer ?? view('components.footer') }}

</body>
</html>
