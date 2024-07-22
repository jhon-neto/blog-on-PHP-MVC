<?php

namespace src\controllers;

use core\Controller;
use src\models\User;
use src\models\Account;

class AdminController extends Controller
{
    private $today;
    private $chart;

    public function __construct()
    {
        session_start();
        if (!$_SESSION['logged']) {
            $this->redirect('/login');
            exit;
        }
        date_default_timezone_set('America/Sao_Paulo');
        $this->today = date('Y-m-d H:i:s');
        $this->chart = [];
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
    }

    public function admin()
    {
        $chart = [
            'chart' => [
                '2024' => '50,94,34,82,51,67,95,24,65,87,10,45',
                '2025' => '46,87,26,54,62,98,21,64,97,25,19,98',
                '2026' => '64,78,95,43,21,14,58,76,54,89,87,59'
            ]
        ];

        $this->render('admin', $chart);
    }

    public function setTheme()
    {
        $theme = filter_input(INPUT_POST, 'theme');

        switch ($_SESSION['permission']) {
            case 'Admin':
                User::update()->set('theme', $theme)->where('id', $_SESSION['id'])->execute();
                break;
            case 'Professor':
                Teacher::update()->set('theme', $theme)->where('id', $_SESSION['id'])->execute();
                break;
            case 'Aluno':
                Student::update()->set('theme', $theme)->where('id', $_SESSION['id'])->execute();
                break;
            case 'Empresa':
                Account::update()->set('theme', $theme)->where('id', $_SESSION['id'])->execute();
                break;
        }
        $_SESSION['theme'] = $theme;
    }

}
