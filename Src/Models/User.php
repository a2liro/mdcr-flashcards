<?php

namespace MDCR\Models;

use App\Services\User\GetAuthenticatedUserService;
use App\Services\User\GetCurrentUserService;
use MDCR\core\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fields = [
        'name' => ['string'],
        'email' => ['string, unique'],
        'password' => ['string'],
        'type' => ['string'],
        'organization_id' => ['integer'],
        'created_at' => ['datetime'],
        'updated_at' => ['datetime'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }

    public function getCurrentUser()
    {
        return GetAuthenticatedUserService::run();
    }
}
