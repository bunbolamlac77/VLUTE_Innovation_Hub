<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Flash Messages -->
            @if (session('success') || session('status') || session('error') || session('info'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    @if (session('success') || session('status'))
                        <div class="mb-3 rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-800 px-4 py-3">
                            {{ session('success') ?? session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-3 rounded-xl border border-rose-300 bg-rose-50 text-rose-800 px-4 py-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="mb-3 rounded-xl border border-sky-300 bg-sky-50 text-sky-800 px-4 py-3">
                            {{ session('info') }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
