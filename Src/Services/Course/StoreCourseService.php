<?php

namespace App\Services\Course;

use App\Repositories\Course\StoreCourseRepository;
use App\Repositories\Course\StoreDeckRepository;
use MDCR\core\File;
use MDCR\Models\Course;
use MDCR\Models\User;

class StoreCourseService
{
    public function run($data)
    {
        $storeCourseRepository = new StoreCourseRepository();
        $user = new User();
        $user->getCurrentUser();
        $data['user_id'] = $user->id;
        if ($_FILES['thumbnail']['size']) {
            $data['thumbnail'] = File::save($_FILES['thumbnail'], 'courses');
        }
        return $storeCourseRepository->run($data, $user);
    }
}
