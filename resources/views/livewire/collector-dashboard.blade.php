<div>
    <h1 class="text-2xl font-bold mb-4">Collector Dashboard</h1>

    <button id="toggle-available"
            class="px-4 py-2 rounded text-white {{ $isAvailable ? 'bg-green-700' : 'bg-gray-500' }}">
        {{ $isAvailable ? 'Available - tap to go offline' : 'Offline - tap to go online' }}
    </button>

    <h2 class="text-lg font-semibold mt-6">Incoming Requests</h2>
    <div class="space-y-2">
        @forelse ($incoming as $req)
            <div class="border rounded p-3 flex justify-between items-center">
                <span>#{{ $req->id }} - {{ $req->waste_type }} ({{ $req->estimated_kg }}kg)</span>
                <button wire:click="acceptRequest({{ $req->id }})" class="bg-blue-600 text-white px-3 py-1 rounded">
                    Accept
                </button>
            </div>
        @empty
            <p class="text-gray-500">No incoming requests right now.</p>
        @endforelse
    </div>

    <h2 class="text-lg font-semibold mt-6">Active Jobs</h2>
    <div class="space-y-2">
        @foreach ($active as $req)
            <div class="border rounded p-3">
                <p>#{{ $req->id }} - en route</p>
                <form wire:submit="markCollected({{ $req->id }})" class="mt-2 flex gap-2 items-center">
                    <input type="file" wire:model="proofPhoto" accept="image/*">
                    <button class="bg-green-700 text-white px-3 py-1 rounded">Mark Collected</button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        document.getElementById('toggle-available').addEventListener('click', () => {
            navigator.geolocation.getCurrentPosition((pos) => {
                @this.call('toggleAvailability', pos.coords.latitude, pos.coords.longitude);
            });
        });

        // Ping location every 10s while online - powers the live tracking map
        setInterval(() => {
            navigator.geolocation.getCurrentPosition((pos) => {
                @this.call('pingLocation', pos.coords.latitude, pos.coords.longitude);
            });
        }, 10000);
    });
</script>
