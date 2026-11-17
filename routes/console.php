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
        $totalMessage = $reserve->location_id->label() . "へ冒険に出かけた!\n\n";

        $dead = false;

        for($i = 0; $i < $actionCount; $i++)
        {
            $message = "";
            $eventType = rand(0, 2);
            switch($eventType)
            {
                // 宝箱処理
                case 0:
                    ['gold' => $gold, 'exp' => $exp, 'damage' => $damage, 'message' => $subMessage] = getTreasure($reserve->location_id, $reserve->stance_id);

                    $totalGold += $gold;
                    $totalExp += $exp;
                    $totalDamage += $damage;
                    $message .= $subMessage;
                    break;
                // 戦闘処理
                case 1:
                    $exp = rand(5, 10);
                    $damage = rand(5, 10);
                    $hp -= $damage;

                    $message .= "ゴブリンに出会った!\n{$damage} のダメージを受けた。";

                    if($hp <= 0)
                    {
                        $dead = true;
                        $message .= "\nHPが0になった。";
                        break;
                    }

                    $message .= "ゴブリンを倒してexp{$exp} を獲得\n";

                    $totalExp += $exp;
                    $subExp += $exp;
                    $totalDamage += $damage;
                    break;
                // 適当処理
                case 2:
                    $message .= "奥に進んだ\n";
                    break;
            }

            // 死亡時ゴールドを0に
            if($dead)
            {
                $totalGold = 0;
                break;
            }

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
                    $max_hp = (int)(100 + $level * 3.6);
                    $attack = (int)(10 + $level * 2.8);
                }
                else
                {
                    break;
                }
            }

            $totalMessage .= $message . "\n";
        }

        if($dead)
        {
            $totalMessage .= "死亡した";
        }
        else
        {
            $totalMessage .= "帰還した";
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

function getTreasure($location_id, $stance_id)
{
    $gold = 0;
    $exp = 0;
    $damage = 0;
    $message = "";

    // ミミックの出現確率
    $mimicRate = match($location_id)
    {
        Location::FOREST => 5,
        Location::CAVE => 50,
        Location::MOUNTAIN => 30,
    };

    // ミミック警戒度
    $vigilanceRate = match($stance_id)
    {
        Stance::CAUTIOUS => 100,
        Stance::NORMAL => 50,
        Stance::PROACTIVE => 0,
    };

    // ミミック判定
    $isMimic = (rand(1, 100) <= $mimicRate);
    $isAttack = (rand(1, 100) <= $vigilanceRate);

    if($isMimic)
    {
        $exp = match($location_id)
        {
            Location::FOREST => rand(5, 10),
            Location::CAVE => rand(15, 30),
            Location::MOUNTAIN => rand(45, 80)
        };

        if($isAttack)
        {
            $message = "宝箱を攻撃したら、ミミックだった!\nミミックを倒して経験値を {$exp} 獲得\n";
        }
        else
        {
            $damage = match($location_id)
            {
                Location::FOREST => rand(5, 10),
                Location::CAVE => rand(15, 25),
                Location::MOUNTAIN => rand(45, 60)
            };

            $message = "宝箱を開けたら、ミミックだった!\n{$damage} のダメージを受けた。 ミミックを倒して経験値を {$exp} 獲得\n";
        }
    }
    else
    {
        // ゴールド
        $gold = match($location_id)
        {
            Location::FOREST => match($stance_id)
            {
                Stance::CAUTIOUS => rand(10, 100),
                Stance::NORMAL => rand(100, 300),
                Stance::PROACTIVE => rand(500, 700),
            },
            Location::CAVE => match($stance_id) {
                Stance::CAUTIOUS => rand(1000, 1200),
                Stance::NORMAL => rand(1200, 1500),
                Stance::PROACTIVE => rand(1800, 2000),
            },
            Location::MOUNTAIN => match($stance_id) {
                Stance::CAUTIOUS => rand(3000, 3200),
                Stance::NORMAL => rand(3500, 3800),
                Stance::PROACTIVE => rand(4000, 5000),
            },
        };

        $message = "宝箱を見つけた!\n{$gold}G を手に入れた\n";

        if($isAttack)
        {
            $loss = round($gold * (rand(1, 100) / 100));
            $gold -= $loss;

            $message = "ミミックを警戒して宝箱攻撃した!\n宝箱の中身が壊れて {$loss}G 失って {$gold}G を手に入れた\n";
        }
    }

    return [
        'gold' => $gold,
        'exp' => $exp,
        'damage' => $damage,
        'message' => $message
    ];
}