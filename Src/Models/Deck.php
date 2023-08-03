<?php

namespace MDCR\Models;

use MDCR\core\Model;

class Deck extends Model
{
    protected $table = 'deck';
    protected $fields = [
        'id' => ['integer'],
        'user_id' => ['integer'],
        'name' => ['string'],
        'description' => ['string'],
        'is_english' => ['boolean'],
        'category_id' => ['integer'],
        'thumbnail' => ['string'],
        'created_at' => ['datetime'],
        'updated_at' => ['datetime'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
