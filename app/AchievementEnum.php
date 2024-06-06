<?php

namespace App;

enum AchievementEnum
{
    case DEFAULT;
    case BRONZE;
    case SILVER;
    case GOLD;

    public static function getReward(int $percentage): self
    {
        if (!$percentage) {
            return self::DEFAULT;
        }

        if ($percentage <= 33) {
            return self::BRONZE;
        }

        if ($percentage <= 66) {
            return self::SILVER;
        }

        return self::GOLD;
    }

    public function toString(): string
    {
        return match ($this) {
            self::DEFAULT => 'Default',
            self::BRONZE => 'Bronze',
            self::SILVER => 'Silver',
            self::GOLD => 'Gold',
        };
    }
}
