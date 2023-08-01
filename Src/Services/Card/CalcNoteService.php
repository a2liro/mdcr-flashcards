<?php

namespace App\Services\Card;

class CalcNoteService
{
    public static function run(int $note, array $lastFive = []): int
    {
        $weight = 0;
        $lastFive = array_reverse($lastFive);
        foreach ($lastFive as $key => $playedCard) {
//            $playedCard = $playedCard->toArray();
            echo '--';
            print_r($playedCard->difficulty);
            print_r($key);
            echo '--|';
            $weight += ($key + 2) * ($playedCard->difficulty + 1) * ($note + 1);
        }

//        var_dump($weight);
//        die();


        return CalcNoteService::setTime($weight);
    }

    private static function setTime($value): int
    {
        if ($value >= 688) {
            return 10;
        } elseif ($value >= 656) {
            return 60;
        } elseif ($value >= 624) {
            return 60 * 6;
        } elseif ($value >= 592) {
            return 60 * 24;
        }
        elseif ($value >= 560) {
            return 60 * 72;
        }
        elseif ($value >= 528) {
            return 60 * 24 * 7;
        }
        elseif ($value >= 496) {
            return 60 * 24 * 20;
        }
        elseif ($value >= 464) {
            return 60 * 24 * 60;
        }
        elseif ($value >= 432) {
            return 60 * 24 * 180;
        }elseif ($value >= 400) {
            return 60;
        } elseif ($value >= 368) {
            return 60 * 6;
        } elseif ($value >= 336) {
            return 60 * 24;
        }
        elseif ($value >= 304) {
            return 60 * 72;
        }
        elseif ($value >= 272) {
            return 60 * 24 * 7;
        }
        elseif ($value >= 240) {
            return 60 * 24 * 20;
        }
        elseif ($value >= 208) {
            return 60 * 24 * 60;
        }
        elseif ($value >= 176) {
            return 60 * 24 * 180;
        }elseif ($value >= 144) {
            return 60 * 24 * 180;
        } else {
            return 60 * 24 * 270;
        }
    }
}
