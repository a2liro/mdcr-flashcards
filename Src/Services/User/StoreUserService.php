<?php
namespace App\Services\User;
use MDCR\Models\User;


class StoreUserService {
    public static function run(array $data) {
        $originalData = $data;
        $userModel = new User();
        if (isset($data['password'])) {
            $options = [
                'cost' => 10
            ];
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, $options);
        }
        $newUser = $userModel->create($data);
        if ($newUser) {

            $userlogged = ApiLoginService::run($originalData);
            if ($userlogged) {
                return $userlogged;
            } else {
                return [];
            }
        }

        return [];
    }
}
