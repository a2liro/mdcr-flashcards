<?php

namespace MDCR\Models;

use MDCR\core\Model;

class Organization extends Model
{
    protected $table = 'organization';
    protected $fields = [
        'id' => ['integer'],
        'user_id' => ['integer'],
        'name' => ['string'],
        'tax_number' => ['string,unique'],
        'created_at' => ['datetime'],
        'updated_at' => ['datetime'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
