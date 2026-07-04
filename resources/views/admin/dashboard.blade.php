<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-emerald-900 mb-1">Admin Dashboard</h1>
        <p class="text-emerald-600 mb-8">Welcome back, {{ auth()->user()->name }}.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white border border-emerald-100 rounded-2xl p-6 shadow-sm">
                <div class="text-3xl mb-2">👤</div>
                <p class="text-sm text-emerald-500 font-medium">Total Users</p>
                <p class="text-3xl font-bold text-emerald-900">{{ \App\Models\User::where('role', 'user')->count() }}</p>
            </div>
            <div class="bg-white border border-emerald-100 rounded-2xl p-6 shadow-sm">
                <div class="text-3xl mb-2">🚛</div>
                <p class="text-sm text-emerald-500 font-medium">Collectors</p>
                <p class="text-3xl font-bold text-emerald-900">{{ \App\Models\User::where('role', 'collector')->count() }}</p>
            </div>
            <div class="bg-white border border-emerald-100 rounded-2xl p-6 shadow-sm">
                <div class="text-3xl mb-2">📦</div>
                <p class="text-sm text-emerald-500 font-medium">Total Requests</p>
                <p class="text-3xl font-bold text-emerald-900">{{ \App\Models\WasteRequest::count() }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
