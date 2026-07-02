<x-app-layout>
    <h1 class="text-2xl font-bold mb-4">Admin dashboard</h1>
    <p class="text-gray-600 mb-6">Welcome, {{ auth()->user()->name }}.</p>

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white border rounded p-4">
            <p class="text-sm text-gray-500">Total users</p>
            <p class="text-2xl font-bold">{{ \App\Models\User::where('role', 'user')->count() }}</p>
        </div>
        <div class="bg-white border rounded p-4">
            <p class="text-sm text-gray-500">Collectors</p>
            <p class="text-2xl font-bold">{{ \App\Models\User::where('role', 'collector')->count() }}</p>
        </div>
        <div class="bg-white border rounded p-4">
            <p class="text-sm text-gray-500">Total requests</p>
            <p class="text-2xl font-bold">{{ \App\Models\WasteRequest::count() }}</p>
        </div>
    </div>
</x-app-layout>
