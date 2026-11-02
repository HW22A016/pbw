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
    
    echo 1;
    foreach($reserves as $reserve)
    {
        $user = $reserve->user;
        $character = $user->character;
        ['gold' => $gold, 'exp' => $exp, 'hp' => $hp] = match($reserve->location_id)
        {
            Location::FOREST => match($reserve->stance_id) {
                Stance::CAUTIOUS => ['gold' => rand(10, 100), 'exp' => rand(10, 15), 'hp' => 0],
                Stance::NORMAL => ['gold' => rand(100, 300), 'exp' => rand(10, 30), 'hp' => rand(0, -5)],
                Stance::PROACTIVE => ['gold' => rand(500, 700), 'exp' => rand(50, 100), 'hp' => rand(-5, -10)],
            },
            Location::CAVE => match($reserve->stance_id) {
                Stance::CAUTIOUS => ['gold' => rand(1000, 1200), 'exp' => rand(100, 200), 'hp' => rand(-10, -15)],
                Stance::NORMAL => ['gold' => rand(1200, 1500), 'exp' => rand(200, 300), 'hp' => rand(-15, -20)],
                Stance::PROACTIVE => ['gold' => rand(1800, 200), 'exp' => rand(500, 700), 'hp' => rand(-20, -25)],
            },
            Location::MOUNTAIN => match($reserve->stance_id) {
                Stance::CAUTIOUS => ['gold' => rand(1200, 1400), 'exp' => rand(700, 900), 'hp' => rand(-25, -30)],
                Stance::NORMAL => ['gold' => rand(1400, 1600), 'exp' => rand(900, 1100), 'hp' => rand(-30, -35)],
                Stance::PROACTIVE => ['gold' => rand(1800, 2000), 'exp' => rand(1500, 1700), 'hp' => rand(-35, -40)],
            },
        };
        echo 2;

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