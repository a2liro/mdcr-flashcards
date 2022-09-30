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
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
