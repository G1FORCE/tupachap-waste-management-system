<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TupaChap') }}</title>

    <!-- Leaflet - free open-source maps, no API key needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-emerald-50/40 text-gray-900 antialiased">

    <nav class="bg-white border-b border-emerald-100 p-4 flex justify-between items-center shadow-sm">
        <a href="/dashboard" class="font-bold text-xl text-emerald-800 tracking-tight">TupaChap</a>
        @auth
            <div class="flex items-center gap-4 text-sm">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full font-medium capitalize">
                    {{ auth()->user()->name }} · {{ auth()->user()->role }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Log Out</button>
                </form>
            </div>
        @endauth
    </nav>

    <main class="max-w-4xl mx-auto p-6">
        {{ $slot }}
    </main>

    @livewireScripts
    <!-- Laravel Echo + Reverb client - powers the real-time updates -->
    <script>        window.Echo?.connector?.pusher?.connection?.bind('connected', () => console.log('Reverb connected'));
    </script>
</body>
</html>
