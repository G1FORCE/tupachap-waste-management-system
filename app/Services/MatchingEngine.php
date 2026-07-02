<?php

namespace App\Services;

use App\Models\WasteRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * MatchingEngine
 * ----------------
 * Objective 3: connects a user's waste request to the nearest suitable
 * collector, based on:
 *   1. Proximity   -> PostGIS ST_Distance on the `geo` geography columns
 *   2. Availability -> collector_locations.is_available = true
 *   3. Capacity     -> the collector's active vehicle can handle the load
 *
 * How it works, in plain language (useful for your presentation):
 *   - Every collector's phone periodically POSTs its GPS coords, which we
 *     store in collector_locations. We only keep the latest ping per collector.
 *   - When a request comes in, we ask PostGIS: "of all collectors who are
 *     currently available, which one's last known location is physically
 *     closest to the pickup point, in meters?"
 *   - We then filter that shortlist down to collectors whose vehicle can
 *     carry the estimated waste weight.
 *   - The closest one that passes the capacity check gets the job.
 */
class MatchingEngine
{
    /**
     * @param  WasteRequest  $request
     * @param  float  $radiusMeters  how far we're willing to search (default 8km)
     * @return User|null  the matched collector, or null if none available
     */
    public function findCollectorFor(WasteRequest $request, float $radiusMeters = 8000): ?User
    {
        // Raw PostGIS query: distance in meters from the request's pickup point
        // to each available collector's most recent location ping.
        $candidates = DB::table('collector_locations as cl')
            ->select('cl.collector_id', DB::raw('ST_Distance(cl.geo, wr.geo) as distance_m'))
            ->crossJoin(DB::raw('(select geo from waste_requests where id = ' . (int) $request->id . ') as wr'))
            ->where('cl.is_available', true)
            // only the latest ping per collector
            ->whereIn('cl.id', function ($q) {
                $q->selectRaw('max(id)')->from('collector_locations')->groupBy('collector_id');
            })
            ->whereRaw('ST_DWithin(cl.geo, wr.geo, ?)', [$radiusMeters])
            ->orderBy('distance_m')
            ->get();

        foreach ($candidates as $candidate) {
            $collector = User::with('vehicles')->find($candidate->collector_id);

            if (! $collector) {
                continue;
            }

            $hasCapableVehicle = $collector->vehicles
                ->first(fn ($v) => $v->canHandle($request->estimated_kg));

            if ($hasCapableVehicle) {
                return $collector; // closest collector that can actually carry the load
            }
        }

        return null; // nobody nearby can take this job right now
    }

    /**
     * Assigns the request to a collector and flips its status.
     * Call this after findCollectorFor() returns a match.
     */
    public function assign(WasteRequest $request, User $collector): WasteRequest
    {
        $request->update([
            'collector_id' => $collector->id,
            'status' => 'matched',
        ]);

        broadcast(new \App\Events\RequestStatusUpdated($request));

        return $request;
    }
}
