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

    $publicCourses = $course->findAllByParams(['type' => 'public']);
    $coursesByMyOrganization = $course->findAllByParams(['type' => 'private', 'organization_id' => $user->organization_id]);
    var_dump($coursesByMyOrganization, $user->organization_id);

    $allMyCourses = array_merge($publicCourses, $coursesByMyOrganization);

    return $allMyCourses;
  }
}
