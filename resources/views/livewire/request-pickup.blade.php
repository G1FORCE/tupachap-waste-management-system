<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-emerald-900">Request a Pickup</h1>
        <p class="text-emerald-600 mt-1">Drop a pin on your location and we'll match you with the nearest collector.</p>
    </div>

    @if (session('message'))
        <div class="bg-emerald-100 text-emerald-800 p-4 rounded-xl mb-6 border border-emerald-200">{{ session('message') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-6">
        <div id="map" style="height: 300px;" class="rounded-xl mb-6 border border-emerald-200"></div>

        @error('lat') <p class="text-red-600 text-sm mb-4 bg-red-50 p-3 rounded-lg">📍 Please click the map to set a pickup location.</p> @enderror

        <form wire:submit="submit" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-emerald-900 mb-1">Waste type</label>
                <select wire:model="wasteType" class="w-full border border-emerald-200 rounded-xl p-3 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    <option value="general">🗑️ General</option>
                    <option value="recyclable">♻️ Recyclable</option>
                    <option value="organic">🍃 Organic</option>
                    <option value="hazardous">⚠️ Hazardous</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-emerald-900 mb-1">Estimated weight (kg)</label>
                <input type="number" wire:model="estimatedKg" class="w-full border border-emerald-200 rounded-xl p-3 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-emerald-900 mb-1">When</label>
                <select wire:model.live="type" class="w-full border border-emerald-200 rounded-xl p-3 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    <option value="on_demand">⚡ Now (on-demand)</option>
                    <option value="scheduled">📅 Schedule for later</option>
                </select>
            </div>

            @if ($type === 'scheduled')
                <div>
                    <label class="block text-sm font-semibold text-emerald-900 mb-1">Date & time</label>
                    <input type="datetime-local" wire:model="scheduledAt" class="w-full border border-emerald-200 rounded-xl p-3">
                </div>
            @endif

            <input type="hidden" wire:model="lat" id="lat">
            <input type="hidden" wire:model="lng" id="lng">

            <button type="submit" class="w-full bg-emerald-700 text-white py-3.5 rounded-xl font-semibold hover:bg-emerald-800 shadow-lg shadow-emerald-100 transition">
                Confirm Pickup Request
            </button>
        </form>
    </div>
</div>

<script>
    (function () {
        const container = document.getElementById('map');
        if (!container || container._leaflet_id) return;

        const map = L.map(container).setView([-6.7924, 39.2083], 13);
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
    })();
</script>

