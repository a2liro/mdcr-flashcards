<?php

namespace App\Repositories\Course;

use MDCR\Models\PlayedCard;
use MDCR\Models\User;
use MDCR\Models\Course;
use MDCR\Models\Deck;

class GetCourseCategoriesAndDecksRepository
{
    public function run($courseId): array
    {

        $user = new User();
        $user = $user->getCurrentUser();
        $deckModel = new Deck();
        $decks = $deckModel->exec(
            "SELECT deck.* from deck join category on category.id = deck.category_id
      join course on course.id = category.course_id
      where course.id = ?",
            [$courseId]
        );
        
        if ($decks) {
            foreach ($decks as $key => $deck) {
                $isPlaying = false;
                $playedCardModel = new PlayedCard();
                $totalCardsToPlayAgain = $playedCardModel
                    ->exec(
                        "SELECT * from (SELECT playedcard.*, MAX(nextshow) nextshowmax from card right
                join playedcard ON card.id = playedcard.card_id 
                where playedcard.user_id = ?
                and playedcard.deck_id = ?
                GROUP by playedcard.card_id) as t where t.nextshowmax < ?;",
                        [$user->id, $deck['id'], date('Y-m-d H:i:s')]
                    );
                $decks[$key]['totalCardsToPlayAgain'] = sizeof($totalCardsToPlayAgain);

                $totalNewCards = 0;
                $allCardsFromDeck = $playedCardModel->exec('select id  from card where deck_id = ?;', [$deck['id']]);
                foreach ($allCardsFromDeck as $card) {
                    $playedcard = $playedCardModel->exec('select id  from playedcard where card_id = ? and user_id = ?;', [$card['id'], $user->id]);
                    if (sizeof($playedcard) == 0) {
                        $totalNewCards++;
                    } else {
                        $isPlaying = true;
                    }
                }
                $decks[$key]['totalNewCards'] = $totalNewCards;
                $decks[$key]['isPlaying'] = $isPlaying;
            }
        }
        
        usort($decks, function($a, $b) {
            return $a['totalCardsToPlayAgain'] < $b['totalCardsToPlayAgain'];
        });
        return $decks;
    }

    
}
