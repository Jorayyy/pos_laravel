<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'VetPOS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    @auth
        <script>window.location.href = '{{ url('/dashboard') }}';</script>
    @else
        <script>window.location.href = '{{ route('login') }}';</script>
    @endauth
</body>
</html>
