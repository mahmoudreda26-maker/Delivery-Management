<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

use App\Models\User;


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

<<<<<<< Updated upstream
Broadcast::channel('vehicles', function (User $user) {
   
    return $user->role === 'manager';
});
=======

Broadcast::channel('vehicles', function (User $user) {
  
    return !is_null($user);
});

>>>>>>> Stashed changes
