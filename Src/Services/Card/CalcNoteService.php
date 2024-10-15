<?php

namespace App\Services\Card;

class CalcNoteService
{
    public static function run(int $note, array $lastFive = []): int
    {
        $weight = 0;
        $lastFive = array_reverse($lastFive);
        for ($count = 5 - sizeof($lastFive); $count > 0; $count--) {
            $obj = (object) array('difficulty' => 5);
            array_unshift($lastFive, $obj);
        }
        foreach ($lastFive as $key => $playedCard) {
            $weight += ($key + 2) * ($playedCard->difficulty + 1) * ($note + 1);
        }


        return CalcNoteService::setTime($weight);
    }

    private static function setTime($value): int
    {
        if ($value >= 688) {
            return 20;
        } elseif ($value >= 656) {
            return 40;
        } elseif ($value >= 624) {
            return 80;
        } elseif ($value >= 592) {
            return 120;
        } elseif ($value >= 560) {
            return 200;
        } elseif ($value >= 528) {
            return 333;
        } elseif ($value >= 496) {
            return 550;
        } elseif ($value >= 464) {
            return 913;
        } elseif ($value >= 432) {
            return 1510;
        } elseif ($value >= 400) {
            return 2510;
        } elseif ($value >= 368) {
            return 4166;
        } elseif ($value >= 336) {
            return 8000;
        } elseif ($value >= 304) {
            return 15000;
        } elseif ($value >= 272) {
            return 35000;
        } elseif ($value >= 240) {
            return 60000;
        } elseif ($value >= 208) {
            return 90000;
        } elseif ($value >= 176) {
            return 150000;
        } elseif ($value >= 144) {
            return 259500;
        } else {
            return 459500;
        }
    }
}
