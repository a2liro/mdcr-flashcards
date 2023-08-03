<?php

namespace App\Repositories\PlayedCard;

use App\Models\PlayedCard;
use App\Services\User\GetCurrentUserService;
use MDCR\Models\User;

class GetPlayedCardByCardIdRepository
{
    public static function run(int $cardId)
    {
        $user = GetCurrentUserService::run();
        $playedCard = new PlayedCard();
        return $playedCard->where(['card_id', '=', $cardId], ['user_id', '=', $user->id])->orderBy(['id' => 'desc', 'user_id' => 'asc'])->get();
    }
}
