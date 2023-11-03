<?php

namespace App\Repositories\Course;

use MDCR\Models\User;
use MDCR\Models\Course;

class GetOrganizationsByLoggedUser
{
    public function run($organizationId)
    {

        $user = new User();
        $user = $user->getCurrentUser();
        $organizations = new Course();
        return $course->findAllByParams(['user_id' => $user['id'], 'organization_id' => $organizationId]);
    }
}
