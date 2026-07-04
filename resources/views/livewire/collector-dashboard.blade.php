<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-emerald-900 mb-6">Collector Dashboard</h1>

    <button id="toggle-available"
            class="w-full sm:w-auto px-6 py-3 rounded-xl text-white font-semibold shadow-lg transition {{ $isAvailable ? 'bg-emerald-700 shadow-emerald-100 hover:bg-emerald-800' : 'bg-gray-500 shadow-gray-100 hover:bg-gray-600' }}">
        {{ $isAvailable ? '🟢 Available — tap to go offline' : '⚪ Offline — tap to go online' }}
    </button>

    <h2 class="text-xl font-semibold text-emerald-900 mt-8 mb-3">Incoming Requests</h2>
    <div class="space-y-3">
        @forelse ($incoming as $req)
            <div class="bg-white border border-emerald-100 rounded-2xl p-4 flex justify-between items-center shadow-sm">
                <div>
                    <span class="font-semibold text-emerald-900">#{{ $req->id }}</span>
                    <span class="text-emerald-600 ml-2">{{ ucfirst($req->waste_type) }} · {{ $req->estimated_kg }}kg</span>
                </div>
                <button wire:click="acceptRequest({{ $req->id }})" class="bg-blue-600 text-white px-4 py-2 rounded-xl font-medium hover:bg-blue-700">
                    Accept
                </button>
            </div>
        @empty
            <p class="text-emerald-400 italic">No incoming requests right now.</p>
        @endforelse
    </div>

    <h2 class="text-xl font-semibold text-emerald-900 mt-8 mb-3">Active Jobs</h2>
    <div class="space-y-3">
        @forelse ($active as $req)
            <div class="bg-white border border-emerald-100 rounded-2xl p-4 shadow-sm">
                <p class="font-semibold text-emerald-900 mb-3">#{{ $req->id }} — 🚛 en route</p>
                <form wire:submit="markCollected({{ $req->id }})" class="flex flex-wrap gap-3 items-center">
                    <input type="file" wire:model="proofPhoto" accept="image/*" class="text-sm">
                    <button class="bg-emerald-700 text-white px-4 py-2 rounded-xl font-medium hover:bg-emerald-800">✅ Mark Collected</button>
                </form>
            </div>
        @empty
            <p class="text-emerald-400 italic">No active jobs right now.</p>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        document.getElementById('toggle-available').addEventListener('click', () => {
            navigator.geolocation.getCurrentPosition((pos) => {
                @this.call('toggleAvailability', pos.coords.latitude, pos.coords.longitude);
            });
        });

        setInterval(() => {
            navigator.geolocation.getCurrentPosition((pos) => {
                @this.call('pingLocation', pos.coords.latitude, pos.coords.longitude);
            });
        }, 10000);
    });
</script>
