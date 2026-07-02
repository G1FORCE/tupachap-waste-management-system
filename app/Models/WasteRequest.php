<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteRequest extends Model
{
    protected $fillable = [
        'user_id', 'collector_id', 'pickup_lat', 'pickup_lng', 'address',
        'waste_type', 'estimated_kg', 'status', 'type', 'scheduled_at',
        'proof_photo_path', 'price', 'payment_reference', 'collected_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'collected_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'matched', 'en_route']);
    }
}
