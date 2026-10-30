<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'turn_number',
        'location',
        'stance',
        'message',
        'gained_exp',
        'gained_gold',
        'hp_change',
    ];
}
