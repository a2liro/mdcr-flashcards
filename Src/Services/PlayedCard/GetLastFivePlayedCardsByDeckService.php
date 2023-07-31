<?php

namespace App\Services\PlayedCard;

use App\Repositories\PlayedCard\GetLastFivePlayedCardsByDeckProvider;

class GetLastFivePlayedCardsByDeckService
{

  public static function run($cardId)
  {
      return GetLastFivePlayedCardsByDeckProvider::run(cardId: $cardId);
  }
}
