<?php

namespace MDCR\Models;

use MDCR\core\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fields = [
        'name' => ['string'],
        'email' => ['string, unique'],
        'password' => ['string'],
        'type' => ['string'],
        'created_at' => ['datetime'],
        'updated_at' => ['datetime'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }

    public function getCurrentUser()
    {
        $userId = $_SESSION['user_id'];
        $user = $this->findOneByParams(['id' => $userId]);
        return $user;
    }
}
