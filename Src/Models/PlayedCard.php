<?php

namespace MDCR\Models;

use MDCR\core\Model;

class PlayedCard extends Model
{
    protected $table = 'playedcard';
    protected $fields = [
        'id' => ['integer'],
        'user_id' => ['integer'],
        'card_id' => ['integer'],
        'deck_id' => ['integer'],
        'difficulty' => ['integer'],
        'lastshow' => ['datetime'],
        'nextshow' => ['datetime'],
        'lastinterval' => ['integer'],
        'is_reverse' => ['boolean'],
        'created_at' => ['datetime'],
        'updated_at' => ['datetime'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
