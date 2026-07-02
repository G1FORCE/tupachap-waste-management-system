<div>
    <h1 class="text-2xl font-bold mb-2">Tracking Request #{{ $request->id }}</h1>
    <p class="mb-4">
        Status:
        <span class="font-semibold px-2 py-1 rounded bg-gray-200">{{ $request->status }}</span>
    </p>

    <div id="track-map" style="height: 350px;" class="rounded border"></div>

    @if ($request->status === 'collected')
        <form action="{{ route('payments.initiate', $request) }}" method="POST" class="mt-4 flex gap-2">
            @csrf
            <input type="text" name="phone" placeholder="2547XXXXXXXX" class="border rounded p-2 flex-1">
            <button class="bg-green-700 text-white px-4 py-2 rounded">Pay with M-Pesa</button>
        </form>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        const pickup = [{{ $request->pickup_lat }}, {{ $request->pickup_lng }}];
        const map = L.map('track-map').setView(pickup, 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        L.marker(pickup).addTo(map).bindPopup('Pickup location');

        let collectorMarker = null;

        @if ($collectorLat)
            collectorMarker = L.marker([{{ $collectorLat }}, {{ $collectorLng }}])
                .addTo(map).bindPopup('Collector');
        @endif

        // Real-time: Echo (Reverb) pushes new coordinates here as the
        // collector moves - this is what makes tracking "live".
        window.Echo.channel('request.{{ $request->id }}')
            .listen('.location.updated', (e) => {
                if (!collectorMarker) {
                    collectorMarker = L.marker([e.lat, e.lng]).addTo(map);
                } else {
                    collectorMarker.setLatLng([e.lat, e.lng]);
                }
            })
            .listen('.status.updated', (e) => {
                Livewire.dispatch('$refresh');
            });
    });
</script>
