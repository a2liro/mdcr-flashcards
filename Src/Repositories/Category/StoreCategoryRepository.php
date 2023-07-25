<?php

namespace App\Repositories\Category;

use MDCR\Models\Category;

class StoreCategoryRepository
{
    public function run($data)
    {
        $data['created_at'] = date('Y-m-d h:i:s');
        $data['updated_at'] = date('Y-m-d h:i:s');
        $course = new Category();
        $course->create($data);
        return $course;
    }
}
