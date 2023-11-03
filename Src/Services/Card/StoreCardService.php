<?php

namespace App\Services\Card;

use MDCR\Models\Card;
use MDCR\Models\Note;
use MDCR\Models\User;

class StoreCardService
{
    public function run(int $deckId, int $cardId, int $note)
    {
        $user = (new User())->getCurrentUser();
        $cards = (new Card())->where(
            ['user_id', '=', $user['id']],
            ['id', '=', $cardId]
        )->get();
        $timeToNextShow = CalcNoteService::run($note, $cards[0]->difficulty); //$this->calcNote($note, $cards[0]->difficulty);
        $cards[0]->difficulty = $note;
        $cards[0]->lastshow = date('Y-m-d H:i:s');
        $cards[0]->nextshow = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " +$timeToNextShow minutes"));

        $cards[0]->update();
        $noteData = [
            'user_id' => $user['id'],
            'card_id' => $cardId,
            'note' => $note,
            'showdate' => $cards[0]->lastshow,
            'nextdate' => $cards[0]->nextshow,
            'interval' => $timeToNextShow,
            'isReverse' => false
        ];
        $note = (new Note())->create($noteData);
    }
}
