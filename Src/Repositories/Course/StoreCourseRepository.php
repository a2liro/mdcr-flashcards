<?php

namespace App\Repositories\Course;

use MDCR\Models\User;
use MDCR\Models\Course;

class StoreCourseRepository
{
  public function run($data, $user)
  {
    $course = new Course();
    $course->create($data);
    return $course;
  }
}
