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
                Stance::CAUTIOUS => ['gold' => rand(10, 100), 'exp' => rand(10, 15), 'damage' => 0],
                Stance::NORMAL => ['gold' => rand(100, 300), 'exp' => rand(10, 30), 'damage' => calculationDamage(5, 10)],
                Stance::PROACTIVE => ['gold' => rand(500, 700), 'exp' => rand(50, 100), 'damage' => calculationDamage(10, 15)],
            },
            Location::CAVE => match($reserve->stance_id) {
                Stance::CAUTIOUS => ['gold' => rand(1000, 1200), 'exp' => rand(100, 200), 'damage' => calculationDamage(15, 20)],
                Stance::NORMAL => ['gold' => rand(1200, 1500), 'exp' => rand(200, 300), 'damage' => calculationDamage(20, 25)],
                Stance::PROACTIVE => ['gold' => rand(1800, 200), 'exp' => rand(500, 700), 'damage' => calculationDamage(30, 35)],
            },
            Location::MOUNTAIN => match($reserve->stance_id) {
                Stance::CAUTIOUS => ['gold' => rand(1200, 1400), 'exp' => rand(700, 900), 'damage' => calculationDamage(35, 40)],
                Stance::NORMAL => ['gold' => rand(1400, 1600), 'exp' => rand(900, 1100), 'damage' => calculationDamage(40, 45)],
                Stance::PROACTIVE => ['gold' => rand(1800, 2000), 'exp' => rand(1500, 1700), 'damage' => calculationDamage(45, 50)],
            },
        };

        $sumExp = $character->exp + $exp;
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

        while(true)
        {
            if($nextExp <= $sumExp)
            {
                $level++;
                $sumExp -= $nextExp;
                $nextExp = (int)(10 + $level ** 1.2);
                $max_hp = (int)(100 + $level * 3.6);
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
// })->daily();
})->everyMinute();

function calculationDamage($minLevel, $maxLevel)
{
    return rand((int)(100 + $minLevel * 3.6), (int)(100 + $maxLevel * 3.6));
}