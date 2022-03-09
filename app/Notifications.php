<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable = [
        'type',
        'notificable_id',
        'notificable_type',
        'data'
    ];

    
}
