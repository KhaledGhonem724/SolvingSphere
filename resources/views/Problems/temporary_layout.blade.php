<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.navbar') {{-- optional --}}
    
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
