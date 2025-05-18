<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'My App' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body class="flex flex-col min-h-screen">

    {{-- Navbar Slot, or default navbar --}}
    {{ $navbar ?? view('partials.navbar') }}

    {{-- Main Content --}}
    <main class="flex-1 p-4">
        {{ $slot }}
    </main>

    {{-- Footer Slot, or default footer --}}
    {{ $footer ?? view('partials.footer') }}

</body>
</html>
