<?php

namespace App\Services\User;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MDCR\Models\User;

class GetAuthenticatedUserService
{
    public static function run()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $firstPath = explode('/', $uri)[1];

        if ($firstPath == 'api') {
            $user = new User();
            $envFile = parse_ini_file(dirname(__DIR__) . "/../../.env");
            $token = str_replace('Baerer ', '', $_SERVER['HTTP_AUTHORIZATION']);
            $key = $envFile["SECRET_KEY"];
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $userInDB = $user->findOneByParams(['email' => $decoded->email]);
            if (!$userInDB) {
                return [];
            }
            return $userInDB->toArray();
        } else {
            $user = new User();
            $userId = $_SESSION['user_id'];
            $user = $user->findOneByParams(['id' => $userId]);
            return $user->toArray();
        }

    }
}
