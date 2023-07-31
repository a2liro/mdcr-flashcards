<?php

namespace App\Services\PlayedCard;

use App\Repositories\PlayedCard\GetPlayedCardByCardIdRepository;
use App\Repositories\PlayedCard\StorePlayedCardRepository;
use App\Services\Card\CalcNoteService;
use MDCR\core\File;
use MDCR\Models\Category;
use MDCR\Models\Deck;
use MDCR\Models\Card;
use MDCR\Models\User;

class StorePlayedCardService
{
    public static function run(int $deckId, int $cardId, $note)
    {
//        $playedCardsByCard = GetPlayedCardByCardIdRepository::run($cardId);
        $lastFive = GetLastFivePlayedCardsByDeckService::run($cardId);
        $nextTime = CalcNoteService::run($note, 5, $lastFive);
        var_dump($nextTime);
        die();
        $data = [
            'deck_id' => $deckId,
            'card_id' => $cardId,
            'difficulty' => $note,
        ];

        $playedCard = StorePlayedCardRepository::run($data);

        $deck = new Deck();
        $deckModel = $deck->findOneByParams(['id' => $deckId]);
        $deckModel->ownPlayedCardList[] = $playedCard;

        $card = new Card();
        $cardModel = $deck->findOneByParams(['id' => $deckId]);
        $cardModel->ownPlayedCardList[] = $playedCard;

    }
}
