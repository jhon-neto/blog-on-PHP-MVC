<?php

namespace src\controllers;

use \core\Controller;
use \src\models\User;

class UserController extends Controller
{
    public function index()
    {
        $a = User::select()->get();
        $this->render('user', [
            'cadastro' => $a
        ]);
    }

    public function login()
    {
        $this->render('login');

    }

    public function choice()
    {
        $this->render('login', 'choice');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    public function loginaction()
    {
        $permission = filter_input(INPUT_POST, 'permission');
        $login = filter_input(INPUT_POST, 'login');
        $pass = filter_input(INPUT_POST, 'pass');
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        if ($login && $pass) {

            $data = User::select()->where('login', $login)->one();
            if ($login == $data['login'] && $hash == password_verify($pass, $data['pass'])) {
                $_SESSION['logged'] = $data['login'];
                $_SESSION['id'] = $data['id'];
                $_SESSION['permission'] = $data['permission'];
                $_SESSION['theme'] = $data['theme'];

                $_SESSION['prev'] = json_decode(file_get_contents('https://api.hgbrasil.com/weather?woeid=455827'), true);

                $this->redirect('/Sistema');
                exit;
            }

        }
        $_SESSION['msg'] = true;

        $this->redirect('/login');
    }

    public function edit()
    {
        if ($_SESSION['permission'] != 'Admin') {
            $this->redirect('/Sistema');
            exit;
        }

        $dado = $this->getUser($_SESSION['permission'], $_SESSION['email']);

        $this->render('admin', 'edit-Admin', [
            'data' => $dado
        ]);
    }

    public function editUser()
    {

        $nome_completo = filter_input(INPUT_POST, 'nome_completo');
        $email = filter_input(INPUT_POST, 'email');
        $senha = filter_input(INPUT_POST, 'senha');
        $confirma_senha = filter_input(INPUT_POST, 'senha2');
        $id = filter_input(INPUT_POST, 'id');

        $_SESSION['logged'] = $nome_completo;
        $_SESSION['email'] = $email;

        $std = User::update();

        if(!$this->hashCompare($senha, $confirma_senha)) {
            $std->set('senha', password_hash($senha, PASSWORD_DEFAULT));
        }

        $std->set('nome_completo', $nome_completo)
            ->set('email', $email)
            ->where('id', $id)
            ->execute();

        $_SESSION['edit'] = true;

        $this->redirect('/Meus-dados');
    }
}
