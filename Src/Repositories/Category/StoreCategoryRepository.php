<?php

namespace App\Repositories\Category;

use MDCR\Models\Category;

class StoreCategoryRepository
{
  public function run($data)
  {
    $course = new Category();
    $course->create($data);
    return $course;
  }
}
