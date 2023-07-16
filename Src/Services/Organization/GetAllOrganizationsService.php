<?php

namespace App\Services\Organization;

use App\Repositories\Course\GetCoursesByOrganizationIdRepository;
use App\Repositories\Organization\GetAllOrganizationsRepository;

class GetAllOrganizationsService
{
  private GetAllOrganizationsRepository $getAllOrganizationsRepository;

  public function __construct()
  {
    $this->getAllOrganizationsRepository = new GetAllOrganizationsRepository();
  }
  public function run()
  {

    return $this->getAllOrganizationsRepository->run();
  }
}
