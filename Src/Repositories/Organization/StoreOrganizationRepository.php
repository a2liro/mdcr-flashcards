<?php

namespace App\Repositories\Organization;

use MDCR\Models\Organization;
use MDCR\Models\User;
use MDCR\Models\Deck;

class StoreOrganizationRepository
{
  public function run($data)
  {

    $user = new User();
    $user->getCurrentUser();
    $data['user_id'] = $user->id;
    $organization = new Organization();
    $organization->create($data);
    $user->ownOrganizatiionList[] = $organization;
    $user->store();
    return $organization;
  }
}
