<?php

namespace App\Services\Course;

use App\Repositories\Course\GetCoursesByOrganizationIdRepository;

class GetCoursesByOrganizationIdService
{
  private GetCoursesByOrganizationIdRepository $getCoursesByOrganizationIdRepository;

  public function __construct()
  {
    $this->getCoursesByOrganizationIdRepository = new GetCoursesByOrganizationIdRepository();
  }
  public function run($organizationId)
  {

    return $this->getCoursesByOrganizationIdRepository->run(organizationId: $organizationId);
  }
}
