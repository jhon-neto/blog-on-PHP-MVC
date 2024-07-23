<?php

namespace src\controllers;

use \core\Controller;
use \src\models\Instagram;

class InstagramController extends Controller
{
    public function index()
    {
        $a = Instagram::select()->get();

        $this->render('insta', [
            'data' => $a,
        ]);
    }

    public function update($args)
    {
        $a = Instagram::select()->find($args['id']);

        $this->render('insta-update', [
            'data' => $a
        ]);
    }

    public function create()
    {
        $data['username'] = str_replace('@', '', filter_input(INPUT_POST, 'username'));
        $data['user_id'] = $_SESSION['id'];

        Instagram::insert($data)->execute();

        $_SESSION['result']['class'] = 'btn btn-success btn-lg';
        $_SESSION['result']['msg'] = 'Username foi ADICIONADO com sucesso!';
        $this->redirect('/Instagram');
    }

    public function updateUsername()
    {
        $data['username'] = str_replace('@', '', filter_input(INPUT_POST, 'username'));
        $id = filter_input(INPUT_POST, 'id');

        Instagram::update()
            ->set($data)
            ->where('id', $id)
            ->execute();

        $_SESSION['result']['class'] = 'btn btn-primary btn-lg';
        $_SESSION['result']['msg'] = 'Username foi EDITADO com sucesso!';
        $this->redirect('/Instagram');
    }

    public function delete($args)
    {
        Instagram::delete()->where('id', $args['id'])->execute();

        $_SESSION['result']['class'] = 'btn btn-danger btn-lg';
        $_SESSION['result']['msg'] = 'Username foi EXCLUÍDO com sucesso!';
        $this->redirect('/Instagram');
    }
}
