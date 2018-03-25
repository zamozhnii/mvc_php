<?php

namespace models;
use core\DBDriver;
use core\Validator;


class PostModel extends BaseModel
{
    protected $validator;
    protected $schema = [
        'book_id' => [
            'type' => 'integer',
            'primary' => true
        ],
        'book_title' => [
            'type' => 'string',
            'length' => [50, 150],
            'not_blank' => true,
            'require' => true
        ],
        'book_descr' => [
            'type' => 'string',
            'length' => 'big',
            'require' => true
        ]
    ];

    public function __construct(DBDriver $db, Validator $validator)
    {
        parent::__construct($db, $validator, 'posts');
        $this->validator->setRules($this->schema);
    }
}