<?php

namespace App\Repositories\Deck;

use MDCR\Models\User;
use MDCR\Models\Deck;

class StoreDeckRepository
{
    public function run($data, $user)
    {
        $user = new User();
        $user->getCurrentUser();
        $data['user_id'] = $user->id;
        $data['created_at'] = date('Y-m-d h:i:s');
        $data['updated_at'] = date('Y-m-d h:i:s');
        $data['is_english'] = $data['isEnglish'] === 'on';

        $deck = new Deck();
        $deck->create($data);
        $user->ownDeckList[] = $deck;
        $user->store();
        return $deck;
    }
}
