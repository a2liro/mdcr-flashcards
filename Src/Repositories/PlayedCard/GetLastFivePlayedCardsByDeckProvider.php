<?php

namespace App\Repositories\PlayedCard;

use MDCR\Models\PlayedCard;
use MDCR\Models\User;

class GetLastFivePlayedCardsByDeckProvider
{
  public static function run($cardId): array
  {

    $user = new User();
    $user = $user->getCurrentUser();
    $playedCard = new PlayedCard();
    return $playedCard->where(['card_id', '=', $cardId])
        ->where(['user_id', '=', $user['id']])
        ->where(['was_deleted', '=', '0'])
        ->orderBy(['id' => 'desc'])
        ->limit(5)
        ->get();
  }
}
