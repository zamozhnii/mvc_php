<?php

namespace models;

use core\Validator;
use core\DBDriver;

class UserModel extends BaseModel
{
    protected $schema = [
        'id' => [
            'primary' => true
        ],
        'login' => [
            'type' => 'string',
            'length' => [3, 50],
            'not_blank' => true,
            'require' => true
        ],
        'password' => [
            'type' => 'string',
            'length' => [8, 50],
            'not_blank' => true,
            'require' => true
        ]
    ];
    public function __construct(DBDriver $db, Validator $validator)
    {
        parent::__construct($db, $validator, 'users');
        $this->validator->setRules($this->schema);
    }

    public function signUp(array $fields)
    {
        $this->validator->execute($fields);

        if(!$this->validator->success) {
            // throw new Exception
        }

        $this->add([
            'login' => $this->validator->clean['login'],
            'password' => $this->getHash($this->validator->clean['password'])
        ]);
    }

    public function getHash($password)
    {
        return md5($password . 'JJJ');
    }
}