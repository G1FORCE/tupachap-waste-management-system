<div>
    <h1 class="text-2xl font-bold mb-4">Request a Pickup</h1>

    @if (session('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('message') }}</div>
    @endif

    <div id="map" style="height: 300px;" class="rounded mb-4 border"></div>

    <form wire:submit="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Waste type</label>
            <select wire:model="wasteType" class="w-full border rounded p-2">
                <option value="general">General</option>
                <option value="recyclable">Recyclable</option>
                <option value="organic">Organic</option>
                <option value="hazardous">Hazardous</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Estimated weight (kg)</label>
            <input type="number" wire:model="estimatedKg" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm font-medium">When</label>
            <select wire:model.live="type" class="w-full border rounded p-2">
                <option value="on_demand">Now (on-demand)</option>
                <option value="scheduled">Schedule for later</option>
            </select>
        </div>

        @if ($type === 'scheduled')
            <div>
                <label class="block text-sm font-medium">Date & time</label>
                <input type="datetime-local" wire:model="scheduledAt" class="w-full border rounded p-2">
            </div>
        @endif

        <input type="hidden" wire:model="lat" id="lat">
        <input type="hidden" wire:model="lng" id="lng">

        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded">
            Confirm Pickup Request
        </button>
    </form>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        const map = L.map('map').setView([-6.7924, 39.2083], 13); // Dar es Salaam default
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        let marker;

        function setPoint(lat, lng) {
            if (marker) marker.setLatLng([lat, lng]); else marker = L.marker([lat, lng]).addTo(map);
            @this.set('lat', lat);
            @this.set('lng', lng);
        }

        map.on('click', (e) => setPoint(e.latlng.lat, e.latlng.lng));

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                map.setView([pos.coords.latitude, pos.coords.longitude], 15);
                setPoint(pos.coords.latitude, pos.coords.longitude);
            });
        }
    });
</script>
