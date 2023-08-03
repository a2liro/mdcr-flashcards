<?php

namespace App\Repositories\Card;

class StoreCardRepository
{
    public function run(array $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
    }
}
