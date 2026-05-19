<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('online', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});