<?php

namespace App\Services\User;

use MDCR\Models\User;

class GetCurrentUserService
{
    public static function run() {
        $user = new User();
        return $user->getCurrentUser();
    }
}
