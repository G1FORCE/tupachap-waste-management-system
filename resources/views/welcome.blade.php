<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TupaChap - Smart Waste Collection</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-b from-emerald-50 to-white min-h-screen">

    <nav class="p-6 flex justify-between items-center max-w-6xl mx-auto">
        <span class="text-xl font-bold text-emerald-800">TupaChap</span>
        <div class="space-x-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-emerald-800 font-medium">Log in</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800">Get Started</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-4xl mx-auto text-center px-6 py-24">
        <h1 class="text-5xl font-bold text-emerald-900 mb-6 leading-tight">
            On-demand waste collection,<br>right when you need it.
        </h1>
        <p class="text-lg text-emerald-700 mb-10 max-w-xl mx-auto">
            Request a pickup, track your collector live on the map, and pay instantly with mobile money.
        </p>
        <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-emerald-700 text-white rounded-xl text-lg font-semibold hover:bg-emerald-800 shadow-lg shadow-emerald-200">
            Request a Pickup
        </a>

        <div class="grid grid-cols-3 gap-6 mt-24 text-left">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100">
                <div class="text-3xl mb-3">📍</div>
                <h3 class="font-semibold text-emerald-900 mb-2">Real-time tracking</h3>
                <p class="text-sm text-emerald-600">Watch your collector arrive live on the map.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100">
                <div class="text-3xl mb-3">🚛</div>
                <h3 class="font-semibold text-emerald-900 mb-2">Smart matching</h3>
                <p class="text-sm text-emerald-600">Connected to the nearest available collector automatically.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100">
                <div class="text-3xl mb-3">💳</div>
                <h3 class="font-semibold text-emerald-900 mb-2">Cashless payment</h3>
                <p class="text-sm text-emerald-600">Pay securely with M-Pesa, no cash needed.</p>
            </div>
        </div>
    </main>
</body>
</html>
