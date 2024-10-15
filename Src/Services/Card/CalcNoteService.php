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
            return 25;
        } elseif ($value >= 656) {
            return 50;
        } elseif ($value >= 624) {
            return 100;
        } elseif ($value >= 592) {
            return 180;
        } elseif ($value >= 560) {
            return 300;
        } elseif ($value >= 528) {
            return 433;
        } elseif ($value >= 496) {
            return 650;
        } elseif ($value >= 464) {
            return 1200;
        } elseif ($value >= 432) {
            return 1910;
        } elseif ($value >= 400) {
            return 3510;
        } elseif ($value >= 368) {
            return 5566;
        } elseif ($value >= 336) {
            return 9900;
        } elseif ($value >= 304) {
            return 18000;
        } elseif ($value >= 272) {
            return 38000;
        } elseif ($value >= 240) {
            return 80000;
        } elseif ($value >= 208) {
            return 110000;
        } elseif ($value >= 176) {
            return 190000;
        } elseif ($value >= 144) {
            return 320500;
        } else {
            return 659500;
        }
    }
}
