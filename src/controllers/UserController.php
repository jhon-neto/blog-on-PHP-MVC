<?php

namespace src\controllers;

use \core\Controller;
use \src\models\User;
use \src\models\State;
use \src\models\City;

class UserController extends Controller
{
    public function index()
    {
        $a = User::select()->get();
        $s = State::select()->orderBy('name')->get();

        $this->render('users', [
            'data' => $a,
            'states' => $s
        ]);
    }

    public function update($args)
    {
        $a = User::select()->find($args['id']);
        $s = State::select()->orderBy('name')->get();

        $this->render('user-update', [
            'data' => $a,
            'states' => $s
        ]);
    }

    public function getCity($args)
    {
        $c = City::select()->where('uf_id', $args['id'])->get();
        echo json_encode($c);
    }

    public function login()
    {
        $this->render('login');
    }

    public function loginaction()
    {
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

    public function create()
    {
        $data['login'] = filter_input(INPUT_POST, 'login');

        if (User::select()->where('login', $data['login'])->one()) {
            $_SESSION['result']['class'] = 'btn btn-warning btn-lg';
            $_SESSION['result']['msg'] = 'Usuário já cadastrado, escolha outro login!';
            $this->redirect('/Usuarios');
        }

        $data['pass'] = filter_input(INPUT_POST, 'pass');
        $data['permission'] = filter_input(INPUT_POST, 'permission');
        $data['city'] = filter_input(INPUT_POST, 'city');
        $state = State::select('name')
            ->find(filter_input(INPUT_POST, 'state'));
        $data['state'] = $state['name'];

        User::insert($data)->execute();

        $_SESSION['result']['class'] = 'btn btn-success btn-lg';
        $_SESSION['result']['msg'] = 'Usuário foi ADICIONADO com sucesso!';
        $this->redirect('/Usuarios');
    }

    public function updateUser()
    {
        $data['login'] = filter_input(INPUT_POST, 'login');
        $pass = filter_input(INPUT_POST, 'pass');
        $data['permission'] = filter_input(INPUT_POST, 'permission');
        $data['city'] = filter_input(INPUT_POST, 'city');
        $id = filter_input(INPUT_POST, 'id');
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $state = State::select('name')
            ->find(filter_input(INPUT_POST, 'state'));
        $data['state'] = $state['name'];

        $u = User::select('pass')->find($id);

        $user = User::update();

        if ($hash != password_verify($pass, $u['pass'])) {
            $user->set('pass', $hash);
        }
        $user->set($data)
            ->where('id', $id)
            ->execute();

        $_SESSION['result']['class'] = 'btn btn-primary btn-lg';
        $_SESSION['result']['msg'] = 'Usuário foi EDITADO com sucesso!';
        $this->redirect('/Usuarios');
    }

    public function delete($args)
    {
        User::delete()->where('id', $args['id'])->execute();

        $_SESSION['result']['class'] = 'btn btn-danger btn-lg';
        $_SESSION['result']['msg'] = 'Usuário foi EXCLUÍDO com sucesso!';
        $this->redirect('/Usuarios');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }
}
