<?php

namespace App\Services\Card;

class CalcNoteService
{
    public static function run(int $note, int $cardDifficulty, array $lastFive = []): int
    {
        if($note == 1) {
            switch($cardDifficulty) {
                case 1:
                    return 365 * 1440;
                case 2:
                    return 180 * 1440;
                case 3:
                    return 60 * 1440;
                case 4:
                    return 30 * 1440;
                case 5:
                    return 15 * 1440;
                default:
                    return 0 * 1440;
            }
        }
        if($note == 2) {
            switch($cardDifficulty) {
                case 1:
                    return 180 * 1440;
                case 2:
                    return 60 * 1440;
                case 3:
                    return 30 * 1440;
                case 4:
                    return 15 * 1440;
                case 5:
                    return 8 * 1440;
                default:
                    return 0;
            }
        }

        if($note == 3) {
            switch($cardDifficulty) {
                case 1:
                    return 30 * 1440;
                case 2:
                    return 15 * 1440;
                case 3:
                    return 7 * 1440;
                case 4:
                    return 4 * 1440;
                case 5:
                    return 2 * 1440;
                default:
                    return 0 * 1440;
            }
        }

        if($note == 4) {
            switch($cardDifficulty) {
                case 1:
                    return 5 * 1440 ;
                case 2:
                    return 3 * 1440 ;
                case 3:
                    return 2 * 1440 ;
                case 4:
                    return 1 * 1440 ;
                case 5:
                    return 720;
                default:
                    return 0;
            }
        }

        if($note == 5) {
            switch($cardDifficulty) {
                case 1:
                    return 2 * 1440;
                case 2:
                    return 1 * 1440;
                case 3:
                    return 100;
                case 4:
                    return 20;
                case 5:
                    return 10;
                default:
                    return 0;
            }
        }
        return 0;
    }
}
