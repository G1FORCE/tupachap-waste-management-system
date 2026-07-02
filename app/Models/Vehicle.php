<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['collector_id', 'plate_number', 'type', 'capacity_kg', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    // Simple capacity check used by the matching engine
    public function canHandle(float $estimatedKg): bool
    {
        return $this->is_active && $this->capacity_kg >= $estimatedKg;
    }
}
