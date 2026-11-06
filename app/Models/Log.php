<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Location;
use App\Enums\Stance;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'turn_number',
        'location_id',
        'stance_id',
        'message',
        'gained_exp',
        'gained_gold',
        'hp_change',
    ];

    // データベースの値をEnumクラスに自動変換
    protected function casts(): array
    {
        return [
            'location_id' => Location::class,
            'stance_id' => Stance::class,
        ];
    }
}
