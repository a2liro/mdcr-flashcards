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
    $deck = new Deck();
    $deck->create($data);
    $user->ownDeckList[] = $deck;
    $user->store();
    return $deck;
  }
}
