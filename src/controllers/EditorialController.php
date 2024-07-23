<?php

namespace src\controllers;

use \core\Controller;
use src\models\Editorial;
use \src\models\Instagram;

class EditorialController extends Controller
{
    public function index()
    {
        $a = Editorial::select()->get();

        $this->render('editorial', [
            'data' => $a,
        ]);
    }

    public function update($args)
    {
        $a = Editorial::select()->find($args['id']);

        $this->render('editorial-update', [
            'data' => $a
        ]);
    }

    public function create()
    {
        $data['name'] = filter_input(INPUT_POST, 'name');

        Editorial::insert($data)->execute();

        $_SESSION['result']['class'] = 'btn btn-success btn-lg';
        $_SESSION['result']['msg'] = 'Editorial foi ADICIONADO com sucesso!';
        $this->redirect('/Editoriais');
    }

    public function updateEditorial()
    {
        $data['name'] = filter_input(INPUT_POST, 'name');
        $id = filter_input(INPUT_POST, 'id');

        Editorial::update()
            ->set($data)
            ->where('id', $id)
            ->execute();

        $_SESSION['result']['class'] = 'btn btn-primary btn-lg';
        $_SESSION['result']['msg'] = 'Editorial foi EDITADO com sucesso!';
        $this->redirect('/Editoriais');
    }

    public function delete($args)
    {
        Editorial::delete()->where('id', $args['id'])->execute();

        $_SESSION['result']['class'] = 'btn btn-danger btn-lg';
        $_SESSION['result']['msg'] = 'Editorial foi EXCLUÍDO com sucesso!';
        $this->redirect('/Editoriais');
    }
}
