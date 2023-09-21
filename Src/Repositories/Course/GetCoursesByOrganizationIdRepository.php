<?php

namespace App\Repositories\Course;

use MDCR\Models\User;
use MDCR\Models\Course;

class GetCoursesByOrganizationIdRepository
{
  public function run($organizationId)
  {

    $user = new User();
    $user = $user->getCurrentUser();
    $course = new Course();
    return $course->findAllByParams(['organization_id' => $organizationId]);
  }
}
