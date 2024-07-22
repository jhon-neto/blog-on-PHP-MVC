<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/Sistema', 'AdminController@admin');
$router->post('/setTheme', 'AdminController@setTheme');

$router->get('/Usuarios', 'UserController@index');
$router->get('/login', 'UserController@login');
$router->post('/loginaction', 'UserController@loginaction');
$router->get('/logout', 'UserController@logout');
$router->get('/Meus-dados', 'UserController@edit');
$router->post('/editUser', 'UserController@editUser');
