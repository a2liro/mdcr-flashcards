<?php

namespace App\Repositories\Course;

use MDCR\Models\User;
use MDCR\Models\Course;

class StoreCourseRepository
{
  public function run($data)
  {

    $user = new User();
    $user->getCurrentUser();
    $data = $request->all();
    $data['user_id'] = $user->id;
    $course = new Course();
    $course->create($data);
    $user->ownFlashCardList[] = $course;
    $user->store();
    return $course;
  }
}
