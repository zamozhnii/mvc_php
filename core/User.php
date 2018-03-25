<?php

namespace core;

use models\UserModel;

class User
{
    private $mUser;

    public function __construct(UserModel $mUser)
    {
        $this->mUser = $mUser;
    }

    public function signUp(array $fields)
    {
        // if(!$this->comparePass($fields)) {
        //     return false;
        // }

        $this->mUser->signUp($fields);
    }

    private function comparePass($fields)
    {
        // сравниваем пароли
    }
}