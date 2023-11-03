<?php

namespace App\Services\Deck;

use App\Repositories\Deck\StoreDeckRepository;
use MDCR\core\File;
use MDCR\Models\Category;
use MDCR\Models\User;

class StoreDeckService
{
    public function run($data)
    {
        $storeDeckRepository = new StoreDeckRepository();
        $user = new User();
        $user->getCurrentUser();
        $data['user_id'] = $user['id'];
        if ($_FILES['thumbnail']['size']) {
            $data['thumbnail'] = File::save($_FILES['thumbnail'], 'decks');
        }
        if ($_FILES['audiofile']['size']) {
            $data['audiofile'] = File::save($_FILES['audiofile'], 'decks');
        }
        $deck = $storeDeckRepository->run($data, $user);
        $user->ownDeckList[] = $deck;
        $user->store();

        $category = new Category();
        $categoryModel = $category->findOneByParams(['id' => $data['category_id']]);
        $categoryModel->ownDeckList[] = $deck;
        $categoryModel->store();
    }
}
