<?php

namespace App\Services\Course;

use App\Repositories\Course\GetCourseCategoriesAndDecksRepository;

class GetCourseCategoriesAndDecksService
{
  private GetCourseCategoriesAndDecksRepository $getCourseCategoriesAndDecksRepository;

  public function __construct()
  {
    $this->getCourseCategoriesAndDecksRepository = new GetCourseCategoriesAndDecksRepository();
  }
  public function run($courseId)
  {

    return $this->getCourseCategoriesAndDecksRepository->run(courseId: $courseId);
  }
}
