<?php

namespace App\Livewire;

use App\Models\WasteRequest;
use Livewire\Component;
use Livewire\Attributes\On;

/**
 * Objective 2: real-time status tracking screen. The Blade view opens a
 * Laravel Echo listener on 'request.{id}' and calls Livewire's
 * $wire.locationUpdated()/statusUpdated() when events arrive - no polling.
 */
class LiveTrackingMap extends Component
{
    public WasteRequest $request;
    public ?float $collectorLat = null;
    public ?float $collectorLng = null;

    public function mount(WasteRequest $request)
    {
        $this->request = $request;

        if ($request->collector) {
            $latest = $request->collector->latestLocation;
            $this->collectorLat = $latest?->lat;
            $this->collectorLng = $latest?->lng;
        }
    }

    #[On('echo:request.{request.id},location.updated')]
    public function locationUpdated($event)
    {
        $this->collectorLat = $event['lat'];
        $this->collectorLng = $event['lng'];
    }

    #[On('echo:request.{request.id},status.updated')]
    public function statusUpdated($event)
    {
        $this->request->refresh();
    }

	// DEMO MODE: simulates a successful mobile money confirmation.
    // Real integration (MalipoPay/M-Pesa) is built in MpesaService.php,
    // but requires sandbox approval - this lets the full flow demo today.
    public function simulatePayment()
    {
        $this->request->update([
            'status' => 'paid',
            'payment_reference' => 'DEMO-' . strtoupper(uniqid()),
        ]);

        broadcast(new \App\Events\RequestStatusUpdated($this->request));
        $this->request->refresh();
    }
    public function render()
    {
        return view('livewire.live-tracking-map');
    }
}
