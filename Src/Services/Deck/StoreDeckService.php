<?php

namespace App\Services\Deck;

use App\Repositories\Deck\StoreDeckRepository;
use MDCR\Models\Course;
use MDCR\Models\User;

class StoreDeckService
{
    public function run($data)
    {
        $storeDeckRepository = new StoreDeckRepository();
        $user = new User();
        $user->getCurrentUser();
        $data['user_id'] = $user->id;
        $deck = $storeDeckRepository->run($data, $user);
        $user->ownDeckList[] = $deck;
        $user->store();

        $course = new Course();
        $courseModel = $course->findOneByParams(['id' => $data['course_id']]);
        $courseModel->ownDeckList[] = $deck;
        $courseModel->store();
    }
}
