<?php

namespace App\Services\Course;

use App\Repositories\Course\GetCoursesByOrganizationIdRepository;
use App\Repositories\Course\GetCoursesByUserRepository;

class GetCoursesByUserService
{
  private GetCoursesByUserRepository $getCoursesByUserRepository;

  public function __construct()
  {
    $this->getCoursesByUserRepository = new GetCoursesByUserRepository();
  }
  public function run()
  {

    return $this->getCoursesByUserRepository->run();
  }
}
