<?php

namespace App\Services\Report;
use App\Repositories\Report\OverviewRepository;

class OverviewService
{
  private OverviewRepository $overviewRepository;

  public function __construct()
  {
    $this->overviewRepository = new OverviewRepository();
  }
  public function run()
  {

    return $this->overviewRepository->run();
  }
}
