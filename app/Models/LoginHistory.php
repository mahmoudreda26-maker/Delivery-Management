<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
