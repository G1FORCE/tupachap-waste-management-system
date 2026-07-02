<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isCollector(): bool
    {
        return $this->role === 'collector';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // For users: requests they created
    public function wasteRequests()
    {
        return $this->hasMany(WasteRequest::class, 'user_id');
    }

    // For collectors: requests assigned to them
    public function assignedRequests()
    {
        return $this->hasMany(WasteRequest::class, 'collector_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'collector_id');
    }

    public function locationPings()
    {
        return $this->hasMany(CollectorLocation::class, 'collector_id');
    }

    // Most recent location ping for this collector
    public function latestLocation()
    {
        return $this->hasOne(CollectorLocation::class, 'collector_id')->latestOfMany();
    }
}
