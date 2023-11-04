<?php
namespace App\Services\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MDCR\Models\User;


class ApiLoginService {
    public static function run(array $data) {
        $user = new User();
        $user = $user->findOneByParams(['email' => $data['email']]);
        $authenticad = password_verify($data['password'], $user->password);
        $userData = array();
        if ($authenticad) {
            $envFile = parse_ini_file(dirname(__DIR__) . "/../../.env");
            $key = $envFile["SECRET_KEY"];
            $payload = [
                "password" => $user->password,
                "name" => $user->name,
                "email" => $user->email
            ];

            $token = JWT::encode($payload, $key, 'HS256');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            $userData['user'] = $user->toArray();
            $userData['user']['token'] = $token;
            unset($userData['user']['password']);
        }
        return $userData;
    }
}
