<div>
    <h1 class="text-2xl font-bold mb-2">Tracking Request #{{ $request->id }}</h1>
    <p class="mb-4">
        Status:
        <span class="font-semibold px-2 py-1 rounded bg-gray-200">{{ $request->status }}</span>
    </p>

    <div id="track-map" style="height: 350px;" class="rounded border"></div>

    @if ($request->status === 'collected')
        <div class="mt-6 bg-white rounded-2xl border border-emerald-100 p-6 shadow-sm">
            <p class="text-sm text-emerald-600 mb-3">💳 Collection confirmed — complete payment to finish.</p>
            <button wire:click="simulatePayment" class="w-full bg-emerald-700 text-white py-3 rounded-xl font-semibold hover:bg-emerald-800">
                Pay TZS {{ number_format($request->price ?? 5000) }} via Mobile Money
            </button>
            <p class="text-xs text-gray-400 mt-2">Demo mode — simulates a successful mobile money confirmation.</p>
        </div>
    @endif

    @if ($request->status === 'paid')
        <div class="mt-6 bg-emerald-50 rounded-2xl border border-emerald-200 p-6 text-center">
            <p class="text-emerald-800 font-semibold text-lg">✅ Payment Received</p>
            <p class="text-sm text-emerald-600 mt-1">Reference: {{ $request->payment_reference }}</p>
        </div>
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
