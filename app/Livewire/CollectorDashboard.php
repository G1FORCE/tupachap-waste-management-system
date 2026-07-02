<?php

namespace App\Livewire;

use App\Models\CollectorLocation;
use App\Models\WasteRequest;
use App\Events\CollectorLocationUpdated;
use App\Events\RequestStatusUpdated;
use Livewire\Component;
use Livewire\WithFileUploads;

class CollectorDashboard extends Component
{
    use WithFileUploads;

    public bool $isAvailable = false;
    public $proofPhoto = null;

    public function toggleAvailability(float $lat, float $lng)
    {
        $this->isAvailable = ! $this->isAvailable;

        $ping = CollectorLocation::create([
            'collector_id' => auth()->id(),
            'is_available' => $this->isAvailable,
            'lat' => $lat,
            'lng' => $lng,
        ]);

        broadcast(new CollectorLocationUpdated(auth()->id(), $lat, $lng));
    }

    // Called periodically (every ~10s) by JS in the view while collector is en route
    public function pingLocation(float $lat, float $lng, ?int $requestId = null)
    {
        CollectorLocation::create([
            'collector_id' => auth()->id(),
            'is_available' => $this->isAvailable,
            'lat' => $lat,
            'lng' => $lng,
        ]);

        broadcast(new CollectorLocationUpdated(auth()->id(), $lat, $lng, $requestId));
    }

    public function acceptRequest(WasteRequest $request)
    {
        $request->update(['status' => 'en_route']);
        broadcast(new RequestStatusUpdated($request));
    }

    // Objective 2: digital proof-of-service (photo of collected waste)
    public function markCollected(WasteRequest $request)
    {
        $this->validate(['proofPhoto' => 'required|image|max:5120']);

        $path = $this->proofPhoto->store('proof-of-service', 'public');

        $request->update([
            'status' => 'collected',
            'proof_photo_path' => $path,
            'collected_at' => now(),
        ]);

        broadcast(new RequestStatusUpdated($request));
        $this->reset('proofPhoto');
    }

    public function render()
    {
        return view('livewire.collector-dashboard', [
            'incoming' => WasteRequest::where('collector_id', auth()->id())
                ->where('status', 'matched')->get(),
            'active' => WasteRequest::where('collector_id', auth()->id())
                ->where('status', 'en_route')->get(),
        ]);
    }
}
