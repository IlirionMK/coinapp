<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="h-full bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white">
<div id="app" class="min-h-screen flex flex-col"></div>
</body>
</html>
