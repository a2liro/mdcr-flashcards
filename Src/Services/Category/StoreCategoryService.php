<?php

namespace App\Services\Category;

use App\Repositories\Category\StoreCategoryRepository;
use App\Repositories\Deck\StoreDeckRepository;
use MDCR\core\File;
use MDCR\Models\Course;
use MDCR\Models\User;

class StoreCategoryService
{
    public function run($data)
    {
        $storeCategoryRepository = new StoreCategoryRepository();
        $user = new User();
        $user->getCurrentUser();
        $data['user_id'] = $user['id'];
        if ($_FILES['thumbnail']['size']) {
            $data['thumbnail'] = File::save($_FILES['thumbnail'], 'categories');
        }
        $category = $storeCategoryRepository->run($data);
        $user->ownCategoryList[] = $category;
        return $category;
    }
}
