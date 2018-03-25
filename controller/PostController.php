<?php

namespace controller;

use models\PostModel;
use core\DBConnector;
use core\DBDriver;
use core\Validator;
use core\Exception\ModelException;

class PostController extends BaseController
{
    public function indexAction()
    {
        $this->title .= '::список постов';
        $mPost = new PostModel(
                            new DBDriver(DBConnector::getPDO()),
                            new Validator()
                        );
        $posts = $mPost->getAll();
        $this->content = $this->build(__DIR__ . '/../views/posts.html.php', ['posts' => $posts]);
    }

    public function oneAction()
    {
        $id = $this->request->get('id');
        //var_dump($id);
        $mPost = new PostModel(
                            new DBDriver(DBConnector::getPDO()),
                            new Validator
                        );
        $post = $mPost->getById($id);
        // echo "<pre>";
        // var_dump($post[0]['book_id']);
        // if($this->request->isPost()) {
        //     //обрабатываем форму
        // }

        $this->content = $this->build(__DIR__ . '/../views/post.html.php', [
            'title' => $post['book_title'],
            'content' => $post['book_descr'],
            'lastUpd' => $post['book_price']
            ]);
    }

    public function addAction()
    {
        $this->title .= '::добавить пост';
        if($this->request->isPost()) {
            $mPost = new PostModel(
                                new DBDriver(DBConnector::getPDO()),
                                new Validator()
                            );
            try {
                $id = $mPost->add([
                    'book_title' => $this->request->post('book_title'),
                    'book_descr' => $this->request->post('book_descr'),
                    'book_price' => $this->request->post('book_price')
                ]);

                $this->redirect(sprintf('/post/%s', $id));
            } catch (ModelException $e) {
                $e->getErrors();
            }   
        }
        $this->content = 'Форма ввода полей для статьи';
    }
}