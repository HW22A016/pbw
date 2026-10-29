<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'user_id',
        'level',
        'exp',
        'max_hp',
        'hp',
        'atk',
        'money',
    ];
}
