<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use App\Models\ActionReserve;
use App\Models\Log;
use App\Enums\Location;
use App\Enums\Stance;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// 毎日深夜00:00にサーバーが実行する処理
Schedule::call(function()
{
    // 処理していない予約を取得
    $reserves = ActionReserve::where('is_processed', false)->get();
  
    foreach($reserves as $reserve)
    {
        $user = $reserve->user;
        $character = $user->character;
        ['gold' => $gold, 'exp' => $exp, 'hp' => $hp] = match($reserve->location_id)
        {
            Location::FOREST => [
                'gold' => rand(100, 300),
                'exp' => rand(10, 30),
                'hp' => rand(0, -5),
            ],
            Location::CAVE => [
                'gold' => rand(500, 1000),
                'exp' => rand(100, 200),
                'hp' => rand(-10, -15),
            ],
            Location::MOUNTAIN => [
                'gold' => rand(1500, 3000),
                'exp' => rand(500, 1000),
                'hp' => rand(-20, -50),
            ],
        };

        $character->increment('gold', $gold);
        $character->increment('exp', $exp);
        $character->increment('hp', $hp);

        Log::create([
            'user_id' => $user->id,
            "turn_number" => ($user->logs()->max('turn_number') ?? 0) + 1,
            "location" => $reserve->location_id->label(),
            "stance" => $reserve->stance_id->label(),
            'message' => "exp: +{$exp} gold: +{$gold}G 受けたダメージ: {$hp}",
            "gained_exp" => $exp,
            "gained_gold" => $gold,
            "hp_change" => -$hp,
        ]);

        $reserve->update(['is_processed' => true]);
    }

    // 毎日0時実行
// })->daily();
})->everyMinute();