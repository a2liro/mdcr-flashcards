<?php

namespace App\Services\Category;

use App\Repositories\Category\StoreCategoryRepository;
use App\Repositories\Deck\StoreDeckRepository;
use MDCR\Models\Course;
use MDCR\Models\User;

class StoreCategoryService
{
    public function run($data)
    {
        $storeCategoryRepository = new StoreCategoryRepository();
        $user = new User();
        $user->getCurrentUser();
        $data['user_id'] = $user->id;
        $category = $storeCategoryRepository->run($data);
        $user->ownCategoryList[] = $category;
        return $category;
    }
}
