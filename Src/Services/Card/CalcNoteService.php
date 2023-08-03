<?php

namespace App\Services\Card;

class CalcNoteService
{
    public static function run(int $note, array $lastFive = []): int
    {
        $weight = 0;
        $lastFive = array_reverse($lastFive);
        for($count = 5 -sizeof($lastFive); $count > 0; $count--) {
            $obj = (object)array('difficulty' => 5);
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
            return 10;
        } elseif ($value >= 656) {
            return 20;
        } elseif ($value >= 624) {
            return 40;
        } elseif ($value >= 592) {
            return 80;
        }
        elseif ($value >= 560) {
            return 160;
        }
        elseif ($value >= 528) {
            return 320;
        }
        elseif ($value >= 496) {
            return 640;
        }
        elseif ($value >= 464) {
            return 1280;
        }
        elseif ($value >= 432) {
            return 2560;
        }elseif ($value >= 400) {
            return 5120;
        } elseif ($value >= 368) {
            return 10240;
        } elseif ($value >= 336) {
            return 20480;
        }
        elseif ($value >= 304) {
            return 40960;
        }
        elseif ($value >= 272) {
            return 81920;
        }
        elseif ($value >= 240) {
            return 150000;
        }
        elseif ($value >= 208) {
            return 280000;
        }
        elseif ($value >= 176) {
            return 320000;
        }elseif ($value >= 144) {
            return 390000;
        } else {
            return 450000 - 5*5;
        }
    }
}
