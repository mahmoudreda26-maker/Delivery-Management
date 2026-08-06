<?php

namespace App\Models;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'latitude',
        'longitude',
        'speed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeForVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }
}