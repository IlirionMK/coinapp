<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CoinApp') }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
<div id="app"></div>
</body>
</html>
