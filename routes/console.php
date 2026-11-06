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
        ['gold' => $gold, 'exp' => $exp, 'damage' => $damage] = match($reserve->location_id)
        {
            Location::FOREST => match($reserve->stance_id) {
                Stance::CAUTIOUS => ['gold' => rand(10, 100), 'exp' => rand(15, 20), 'damage' => rand(0, 15)],
                Stance::NORMAL => ['gold' => rand(100, 300), 'exp' => rand(20, 25), 'damage' => calculationDamage(7, 10)],
                Stance::PROACTIVE => ['gold' => rand(500, 700), 'exp' => rand(25, 30), 'damage' => calculationDamage(10, 15)],
            },
            Location::CAVE => match($reserve->stance_id) {
                Stance::CAUTIOUS => ['gold' => rand(1000, 1200), 'exp' => rand(30, 35), 'damage' => calculationDamage(20, 25)],
                Stance::NORMAL => ['gold' => rand(1200, 1500), 'exp' => rand(40, 50), 'damage' => calculationDamage(30, 40)],
                Stance::PROACTIVE => ['gold' => rand(1800, 2000), 'exp' => rand(60, 70), 'damage' => calculationDamage(40, 50)],
            },
            Location::MOUNTAIN => match($reserve->stance_id) {
                Stance::CAUTIOUS => ['gold' => rand(3000, 3200), 'exp' => rand(75, 80), 'damage' => calculationDamage(60, 65)],
                Stance::NORMAL => ['gold' => rand(3500, 3800), 'exp' => rand(90, 100), 'damage' => calculationDamage(70, 80)],
                Stance::PROACTIVE => ['gold' => rand(4000, 5000), 'exp' => rand(110, 120), 'damage' => calculationDamage(90, 100)],
            },
        };

        $nextExp = $character->nextExp;
        $max_hp = $character->max_hp;
        $attack = $character->attack;
        $level = $character->level;
        $message;

        if($max_hp <= $damage)
        {
            $gold = 0;
            $exp = 0;
            $message = "探索失敗";
        }
        else
        {
            $message = "探索成功";
        }

        $sumExp = $character->exp + $exp;

        while(true)
        {
            if($nextExp <= $sumExp)
            {
                $level++;
                $sumExp -= $nextExp;
                $nextExp = (int)(10 + $level ** 1.2);
                $max_hp = (int)(20 + $level * 3.6);
                $attack = (int)(10 + $level * 2.8);
            }
            else
            {
                break;
            }
        }

        $character->level = $level;
        $character->nextExp = $nextExp;
        $character->exp = $sumExp;
        $character->max_hp = $max_hp;
        $character->attack = $attack;
        $character->gold += $gold;
        $character->save();

        Log::create([
            'user_id' => $user->id,
            "turn_number" => ($user->logs()->max('turn_number') ?? 0) + 1,
            "location_id" => $reserve->location_id,
            "stance_id" => $reserve->stance_id,
            'message' => $message,
            "gained_exp" => $exp,
            "gained_gold" => $gold,
            "hp_change" => $damage,
        ]);

        $reserve->update(['is_processed' => true]);
    }

    // 毎日0時実行
})->daily();
// })->everyMinute();

function calculationDamage($minLevel, $maxLevel)
{
    return rand((int)(20 + $minLevel * 3.6), (int)(20 + $maxLevel * 3.6));
}