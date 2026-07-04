<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in - TupaChap</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-b from-emerald-50 to-white min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <span class="text-2xl font-bold text-emerald-800">TupaChap</span>
            <p class="text-emerald-600 mt-1">Welcome back</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg shadow-emerald-100 border border-emerald-100 p-8">

            @if (session('status'))
                <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 p-3 rounded-lg">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-700 bg-red-50 p-3 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-emerald-900 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border border-emerald-200 rounded-xl p-3 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-emerald-900 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-emerald-200 rounded-xl p-3 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                </div>

                <label class="flex items-center gap-2 text-sm text-emerald-700">
                    <input type="checkbox" name="remember" class="rounded border-emerald-300">
                    Remember me
                </label>

                <button type="submit" class="w-full bg-emerald-700 text-white py-3.5 rounded-xl font-semibold hover:bg-emerald-800 shadow-lg shadow-emerald-100 transition">
                    Log In
                </button>
            </form>

            <p class="text-center text-sm text-emerald-600 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-semibold text-emerald-800 hover:underline">Sign up</a>
            </p>
        </div>
    </div>

</body>
</html>
