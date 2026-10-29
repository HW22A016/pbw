<?php

namespace App\Models;

use App\Enums\Location;
use App\Enums\Stance;
use Illuminate\Database\Eloquent\Model;

class ActionReserve extends Model
{
    protected $fillable = [
        'user_id',
        'location_id',
        'stance_id',
        'is_processed',
    ];

    // データベースの値をEnumクラスに自動変換
    protected function casts(): array
    {
        return [
            'location_id' => Location::class,
            'stance_id' => Stance::class,
            // DBでの0や1を自動的にfalseやtrueに変換(キャスト)してくれる
            'is_processed' => 'boolean',
        ];
    }
}
