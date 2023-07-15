<?php

namespace App\Services\Course;

use App\Repositories\Course\StoreDeckRepository;
use MDCR\Models\Course;
use MDCR\Models\User;

class StoreCourseService
{
    public function run($data)
    {
        $storeCourseRepository = new StoreDeckRepository();
        $user = new User();
        $user->getCurrentUser();
        $data['user_id'] = $user->id;
        return $storeCourseRepository->run($data, $user);
    }
}
