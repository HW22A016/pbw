<?php
namespace App\Enums;

enum Location: int
{
    // enumの選択肢
    case FOREST = 1;
    case CAVE = 2;
    case MOUNTAIN = 3;

    public function label(): string
    {
        return match($this)
        {
            // このグループ内の選択肢から選ばれていたら文字列を返す
            self::FOREST => '初心者の森',
            self::CAVE => '迷いの洞窟',
            self::MOUNTAIN => '静かな山道',
        };
    }
}