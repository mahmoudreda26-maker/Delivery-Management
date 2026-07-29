<?php

namespace App\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id',
        'plate_number',
        'model',
        'type',
        'status',
        'year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
    public function latestLocation()
    {
        return $this->hasOne(Location::class)->latestOfMany();
    }
    public function driver(): BelongsTo
    {

        return $this->belongsTo(User::class, 'user_id');
    }
}
