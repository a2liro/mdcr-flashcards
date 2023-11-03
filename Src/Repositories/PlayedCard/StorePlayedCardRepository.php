<?php

namespace App\Repositories\PlayedCard;

use MDCR\Models\User;
use MDCR\Models\PlayedCard;

class StorePlayedCardRepository
{
    public static function run($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $user = (new User())->getCurrentUser();
        $data['user_id'] = intval($user['id']);
        $playedCard = new PlayedCard();
        $playedCard->create($data);
        // $user->ownPlayedCardList[] = $playedCard;
        // $user->store();
        return $playedCard;
    }
}
