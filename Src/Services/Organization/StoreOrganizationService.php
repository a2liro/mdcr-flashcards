<?php

namespace App\Services\Organization;

use App\Repositories\Category\StoreCategoryRepository;
use App\Repositories\Deck\StoreDeckRepository;
use App\Repositories\Organization\StoreOrganizationRepository;
use MDCR\Models\Course;
use MDCR\Models\User;

class StoreOrganizationService
{
    public function run($data)
    {
        $storeOrganizationRepository = new StoreOrganizationRepository();
        $user = new User();
        $user->getCurrentUser();
        $data['user_id'] = $user['id'];
        $organization = $storeOrganizationRepository->run($data);
        $user->ownOrganizationList[] = $organization;
        return $organization;
    }
}
