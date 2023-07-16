<?php

namespace App\Repositories\Organization;

use MDCR\Models\Organization;
use MDCR\Models\User;
use MDCR\Models\Course;

class GetAllOrganizationsRepository
{
    public function run()
    {

        $user = new User();
        $user = $user->getCurrentUser();
        $organization = new Organization();
        return $organization->get();
    }
}
