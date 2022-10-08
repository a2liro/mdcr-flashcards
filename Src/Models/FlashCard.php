<?php

namespace MDCR\Models;

use MDCR\core\Model;

class FlashCard extends Model
{
    protected $table = 'flashcard';
    protected $fields = [
        'id' => ['integer'],
		'user_id' => ['integer'],
        'deck_id' => ['integer'],
        'front' => ['string'],
        'back' => ['string'],
        'difficulty' => ['integer'],
        'lastshow' => ['datetime'],
        'nextshow' => ['datetime'],
        'lastinterval' => ['integer'],
        'audio' => ['string']
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
