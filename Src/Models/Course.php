<?php

namespace MDCR\Models;

use MDCR\core\Model;

class Course extends Model
{
    protected $table = 'course';
    protected $fields = [
        'id' => ['integer'],
        'user_id' => ['integer'],
        'name' => ['string'],
        'description' => ['string'],
        'thumbnail' => ['string'],
        'organization_id' => ['integer'],
        'created_at' => ['datetime'],
        'updated_at' => ['datetime'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
