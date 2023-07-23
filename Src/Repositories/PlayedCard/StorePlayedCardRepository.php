<?php

namespace App\Repositories\PlayedCard;

use MDCR\Models\User;
use App\Models\PlayedCard;

class StorePlayedCardRepository
{
  public static function run($data)
  {
    $user = new User();
    $user->getCurrentUser();
    $data['user_id'] = intval($user->id);
    $playedCard = new PlayedCard();
    var_dump($data);
    $playedCard->create($data);
    $user->ownPlayedCardList[] = $playedCard;
    $user->store();
    return $playedCard;
  }
}
