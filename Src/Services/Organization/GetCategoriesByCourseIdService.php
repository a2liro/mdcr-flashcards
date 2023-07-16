<?php

namespace App\Services\Category;

use App\Repositories\Course\GetCoursesByOrganizationIdRepository;

class GetCategoriesByCourseIdService
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
