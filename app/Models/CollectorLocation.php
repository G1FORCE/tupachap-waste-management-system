<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectorLocation extends Model
{
    protected $fillable = ['collector_id', 'is_available', 'lat', 'lng'];

    protected function casts(): array
    {
        return ['is_available' => 'boolean'];
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }
}
