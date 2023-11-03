<?php

namespace App\Repositories\Deck;

use MDCR\Models\User;
use MDCR\Models\Course;

class GetDecksByCourseIdRepository
{
  public function run($organizationId)
  {

    $user = new User();
    $user = $user->getCurrentUser();
    $course = new Course();
    return $course->findAllByParams(['user_id' => $user['id'], 'organization_id' => $organizationId]);
  }
}
