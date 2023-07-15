<?php

namespace App\Services\Deck;

use App\Repositories\Course\GetDecksByCourseIdRepository;

class GetDeckByCourseIdService
{
  private GetDecksByCourseIdRepository $getCoursesByOrganizationIdRepository;

  public function __construct()
  {
    $this->getCoursesByOrganizationIdRepository = new GetDecksByCourseIdRepository();
  }
  public function run($organizationId)
  {

    return $this->getCoursesByOrganizationIdRepository->run(organizationId: $organizationId);
  }
}
