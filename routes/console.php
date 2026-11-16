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

        $level = $character->level;
        $subExp = $character->exp;
        $nextExp = $character->nextExp;
        $max_hp = $character->max_hp;
        $attack = $character->attack;

        $hp = $max_hp;

        $actionCount = rand(10, 20);

        $totalGold = 0;
        $totalExp = 0;
        $totalDamage = 0;
        $totalMessage = "";

        $dead = false;

        for($i = 0; $i < $actionCount; $i++)
        {
            $eventType = rand(0, 2);
            switch($eventType)
            {
                // 宝箱処理
                case 0:
                    $gold = rand(10, 100);
                    $message = "宝箱を見つけた! {$gold}G を手に入れた\n";

                    $totalGold += $gold;
                    break;
                // 戦闘処理
                case 1:
                    $exp = rand(5, 10);
                    $damage = rand(5, 10);
                    $hp -= $damage;

                    $message = "ゴブリンに出会った! {$damage}のダメージを受けた。";

                    if($hp <= 0)
                    {
                        $dead = true;
                        $message .= "\nHPが0になった。";
                        break;
                    }

                    $message .= " exp{$exp} を獲得\n";

                    $totalExp += $exp;
                    $subExp += $exp;
                    $totalDamage += $damage;

                    // レベルアップ処理
                    $levelUpCount = 0;
                    while(true)
                    {
                        if($nextExp <= $subExp)
                        {
                            $levelUpCount++;
                            $message .= "レベルが {$levelUpCount} あがった!\n";
                            $level++;
                            $subExp -= $nextExp;
                            $nextExp = (int)(10 + $level ** 1.2);
                            $max_hp = (int)(20 + $level * 3.6);
                            $attack = (int)(10 + $level * 2.8);
                        }
                        else
                        {
                            break;
                        }
                    }

                    break;
                // 適当処理
                case 2:
                    $message = "奥に進んだ\n";
                    break;
            }

            $totalMessage .= $message;

            if($dead)
            {
                $totalGold = 0;
                break;
            }
        }

        $character->level = $level;
        $character->nextExp = $nextExp;
        $character->exp = $subExp;
        $character->max_hp = $max_hp;
        $character->attack = $attack;
        $character->gold += $totalGold;
        $character->save();

        Log::create([
            'user_id' => $user->id,
            "turn_number" => ($user->logs()->max('turn_number') ?? 0) + 1,
            "location_id" => $reserve->location_id,
            "stance_id" => $reserve->stance_id,
            'message' => $totalMessage,
            "gained_exp" => $exp,
            "gained_gold" => $totalGold,
            "hp_change" => $totalDamage,
        ]);

        $reserve->update(['is_processed' => true]);
    }

    // 毎日0時実行
// })->daily();
})->everyMinute();

function calculationDamage($minLevel, $maxLevel)
{
    return rand((int)(20 + $minLevel * 3.6), (int)(20 + $maxLevel * 3.6));
}