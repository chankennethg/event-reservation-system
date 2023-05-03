<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <meta name="description" content="{{ config('settings.description') }}">
        <meta name="keywords" content="{{ config('settings.keywords') }}">
        <meta name="author" content="{{ config('settings.author') }}">
        <link rel="icon" href="{{ config('app.url') }}/favicon.ico">
        <link rel="apple-touch-icon" href="{{ config('app.url') }}/apple-touch-icon.png" sizes="180x180">
        <link rel="mask-icon" href="{{ config('app.url') }}/favicon-32x32.png" color="#FFFFFF">
        <meta name="theme-color" content="#ffffff">
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-gray-50 h-screen antialiased leading-none font-sans overflow-x-hidden overflow-y-auto">
        <div id="app"  v-cloak></div>
        @vite(['resources/js/app.js'])
    </body>
</html>
