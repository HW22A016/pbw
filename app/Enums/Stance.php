<?php
namespace App\Enums;

enum Stance: int
{
    case CAUTIOUS = 1;
    case NORMAL = 2;
    case PROACTIVE = 3;

    public function label(): string
    {
        return match($this)
        {
            self::CAUTIOUS => '慎重に進む',
            self::NORMAL => '普通',
            self::PROACTIVE => '積極的に戦う',
        };
    }
}