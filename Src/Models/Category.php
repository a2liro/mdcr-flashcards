<?php

namespace MDCR\Models;

use MDCR\core\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $fields = [
        'id' => ['integer'],
        'user_id' => ['integer'],
        'name' => ['string'],
        'organization_id' => ['integer'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
