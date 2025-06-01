<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'Solving Sphere' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js" async></script>
    <script>
        window.MathJax = {
            tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']],
            displayMath: [['$$', '$$'], ['\\[', '\\]']],
            },
            svg: {
            fontCache: 'global'
            }
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>

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