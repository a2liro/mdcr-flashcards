<?php

namespace App\Services\Deck;

use App\Services\Card\CalcNoteService;
use App\Services\PlayedCard\GetLastFivePlayedCardsByDeckService;
use MDCR\Models\Card;
use MDCR\Models\Deck;
use MDCR\Models\PlayedCard;
use MDCR\Models\User;


class GetNextCardByDeckService
{


  public function __construct()
  {
    //
  }
  public static function run($deckId)
  {

    $card = [];
    $user = (new User())->getCurrentUser();
    $cardModel = new Card();

    $cardsToPlayAgainData = $cardModel->exec('select * from (SELECT card.*, playedcard.nextshow, playedcard.id as playId FROM card RIGHT JOIN playedcard on card.id = playedcard.card_id  WHERE playedcard.id IN (SELECT MAX(playedcard.id) FROM card right join playedcard on card.id = playedcard.card_id where card.deck_id = ? and playedcard.user_id = ? and playedcard.was_deleted = ? GROUP BY card_id)) as d where d.nextshow < NOW() ORDER by d.nextshow', [$deckId, $user->id, 0]);

    if (sizeof($cardsToPlayAgainData)) {
      $card = $cardsToPlayAgainData[0];
    } else {

      $playedCardModel = new PlayedCard();
      $allCardsFromDeck = $cardModel->where(['deck_id', '=', $deckId])->get();
      $cardsPlayedFromUser = $playedCardModel
        ->where(['deck_id', '=', $deckId])
        ->where(['user_id', '=', $user['id']])
        ->where(['was_deleted', '=', 0])
        ->get();

      foreach ($allCardsFromDeck as $key => $item) {
        foreach ($cardsPlayedFromUser as $keyInside => $played) {
          if ($played->card_id === $item->id) {
            unset($allCardsFromDeck[$key]);
          }
        }
      }

      if (sizeof($allCardsFromDeck)) {
        $values = array_values($allCardsFromDeck);
        $card = array_shift($values)->toArray();
      }
    }

    $deck = (new Deck())->findOneByParams(['id' => $deckId]);

    if (gettype($card) == 'array' && !sizeof($card)) {
      return $card;
    }

    $lasFiveNotes = GetLastFivePlayedCardsByDeckService::run($card["id"]);


    for ($count = 1; $count <= 5; $count++) {
      $interval = CalcNoteService::run($count, $lasFiveNotes); //CardController::calcNote($count, $card->difficulty);
      if ($interval < 60) {
        $card['intervals'][$count] = $interval . 'm';
      } else if ($interval < 1440) {
        $card['intervals'][$count] = intdiv($interval, 60) . 'h';
      } else {
        $card['intervals'][$count] = intdiv($interval, 1440) . 'd';
      }
    }

    return $card;

  }
}
