<?php

namespace MDCR\Models;

use MDCR\core\Model;

class Card extends Model
{
    protected $table = 'card';
    protected $fields = [
        'id' => ['integer'],
        'user_id' => ['integer'],
        'deck_id' => ['integer'],
        'front' => ['string'],
        'back' => ['string'],
        'audio' => ['string'],
        'image' => ['string'],
        'audiofile' => ['string'],
        'created_at' => ['datetime'],
        'updated_at' => ['datetime'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
