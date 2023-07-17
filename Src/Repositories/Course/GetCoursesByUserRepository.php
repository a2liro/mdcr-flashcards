<?php

namespace App\Repositories\Course;

use MDCR\Models\User;
use MDCR\Models\Course;

class GetCoursesByUserRepository
{
  public function run()
  {

    $user = new User();
    $user = $user->getCurrentUser();
    $course = new Course();
    return $course->findAllByParams(['user_id' => $user->id]);
  }
}
