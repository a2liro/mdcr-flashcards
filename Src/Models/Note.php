<?php

namespace MDCR\Models;

use MDCR\core\Model;

class Note extends Model
{
    protected $table = 'note';
    protected $fields = [
        'id' => ['integer'],
        'user_id' => ['integer'],
        'card_id' => ['integer'],
        'note' => ['integer'],
        'showdate' => ['datetime'],
        'nextdate' => ['datetime'],
        'interval' => ['integer'],
        'isReverse' => ['boolean'],
        'created_at' => ['datetime'],
        'updated_at' => ['datetime'],
    ];

    public function __construct()
    {
        parent::__construct($this->table, $this->fields);
    }
}
