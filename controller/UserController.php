<?php

namespace controller;

use models\UserModel;
use core\DBConnector;
use core\DBDriver;
use core\Validator;
use core\User;

class UserController extends BaseController
{
    public function signUpAction()
    {
        $errors = [];
        $this->title .= '::Регистрация';

        if($this->request->isPost()) {
            $mUser = new UserModel(
                new DBDriver(DBConnector::getPDO()),
                new Validator()
            );
            $user = new User($mUser);
            try {
                $user->signUp($this->request->post());
                $this->redirect('/');

            } catch(\Exception $e) {
                $errors = $e->getErrors();
            }
        }
       
        $this->content = $this->build(__DIR__ . '/../views/sign-up.html.php', [
            'errors' => $errors
        ]);
    }

    public function signInAction()
    {
        $errors = [];
        $this->title .= '::Авторизация';

        if($this->request->isPost()) {
            $mUser = new UserModel(
                new DBDriver(DBConnector::getPDO()),
                new Validator()
            );
            $user = new User($mUser);
        }

        $this->content = $this->build(__DIR__ . '/../views/sign-in.html.php', [
            'errors' => $errors
        ]);
    }
}