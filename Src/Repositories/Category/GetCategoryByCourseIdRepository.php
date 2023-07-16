<?php

namespace App\Repositories\Category;

use MDCR\Models\User;
use MDCR\Models\Course;

class GetCategoryByCourseIdRepository
{
  public function run($organizationId)
  {

    $user = new User();
    $user = $user->getCurrentUser();
    $course = new Course();
    return $course->findAllByParams(['user_id' => $user->id, 'organization_id' => $organizationId]);
  }
}
