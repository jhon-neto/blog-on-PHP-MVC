<?php

namespace src\controllers;

use \core\Controller;

class HomeController extends Controller
{

    public function index()
    {
        echo 'Site principal!<br><br>';
        echo '<a href="/Blog-Manaus-MVC/home/login">Dashboard</a>';
    }
}