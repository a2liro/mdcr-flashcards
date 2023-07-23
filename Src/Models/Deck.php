<?php

namespace MDCR\Models;

use MDCR\core\Model;

class Deck extends Model
{
    protected $table = 'deck';
    protected $fields = [
        'id' => ['integer'],
        'user_id' => ['integer'],
        'card_id' => ['integer'],
        'deck_id' => ['integer'],
        'create_at' => ['datetime']
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
