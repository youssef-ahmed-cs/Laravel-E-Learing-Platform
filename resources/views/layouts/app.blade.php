<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel Storm') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @yield('head')
</head>
<body>
    <nav class="bg-white border-b mb-4">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a class="text-xl font-bold text-gray-800" href="#">{{ config('app.name', 'Laravel Storm') }}</a>
            <!-- Add navigation links here if needed -->
        </div>
    </nav>
    <div class="container mx-auto px-4">
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>
