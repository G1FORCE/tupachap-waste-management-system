<?php

namespace App\Livewire;

use App\Models\WasteRequest;
use App\Services\MatchingEngine;
use Livewire\Component;

/**
 * Objective 2: lets a logged-in user submit an on-demand or scheduled
 * pickup request. The map (Leaflet, in the Blade view) sets $lat/$lng
 * via JavaScript when the user drops a pin or allows geolocation.
 */
class RequestPickup extends Component
{
    public float $lat;
    public float $lng;
    public string $address = '';
    public string $wasteType = 'general';
    public float $estimatedKg = 10;
    public string $type = 'on_demand';
    public ?string $scheduledAt = null;

    protected $rules = [
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'wasteType' => 'required|in:general,recyclable,organic,hazardous',
        'estimatedKg' => 'required|numeric|min:1|max:2000',
        'type' => 'required|in:on_demand,scheduled',
    ];

    public function submit(MatchingEngine $matcher)
    {
        $this->validate();

        $request = WasteRequest::create([
            'user_id' => auth()->id(),
            'pickup_lat' => $this->lat,
            'pickup_lng' => $this->lng,
            'address' => $this->address,
            'waste_type' => $this->wasteType,
            'estimated_kg' => $this->estimatedKg,
            'type' => $this->type,
            'scheduled_at' => $this->type === 'scheduled' ? $this->scheduledAt : null,
        ]);

        // On-demand requests get matched immediately.
        // Scheduled ones get matched by a queued job closer to the time
        // (see App\Jobs\MatchScheduledRequest, wired up in the console kernel).
        if ($this->type === 'on_demand') {
            $collector = $matcher->findCollectorFor($request);

            if ($collector) {
                $matcher->assign($request, $collector);
                session()->flash('message', "Matched with collector: {$collector->name}");
            } else {
                session()->flash('message', 'Request created - searching for a nearby collector...');
            }
        }

        return redirect()->route('requests.show', $request);
    }

    public function render()
    {
        return view('livewire.request-pickup');
    }
}
