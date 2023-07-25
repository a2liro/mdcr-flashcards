<?php

namespace App\Repositories\Course;

use MDCR\Models\User;
use MDCR\Models\Course;

class StoreCourseRepository
{
    public function run($data, $user)
    {
        $data['created_at'] = date('Y-m-d h:i:s');
        $data['updated_at'] = date('Y-m-d h:i:s');
        $course = new Course();
        $course->create($data);
        return $course;
    }
}
