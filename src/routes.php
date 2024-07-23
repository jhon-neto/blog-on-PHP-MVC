<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/Sistema', 'AdminController@admin');
$router->post('/setTheme', 'AdminController@setTheme');


$router->get('/Usuarios', 'UserController@index');
$router->get('/login', 'UserController@login');
$router->post('/loginaction', 'UserController@loginaction');
$router->post('/user-create', 'UserController@create');
$router->get('/user-update/{id}', 'UserController@update');
$router->post('/user-update', 'UserController@updateUser');
$router->get('/user-delete/{id}', 'UserController@delete');
$router->get('/get-cityes/{id}', 'UserController@getCity');
$router->get('/logout', 'UserController@logout');


$router->get('/Instagram', 'InstagramController@index');
$router->post('/insta-create', 'InstagramController@create');
$router->get('/insta-update/{id}', 'InstagramController@update');
$router->post('/insta-update', 'InstagramController@updateUsername');
$router->get('/insta-delete/{id}', 'InstagramController@delete');


$router->get('/Editoriais', 'EditorialController@index');
$router->post('/editorial-create', 'EditorialController@create');
$router->get('/editorial-update/{id}', 'EditorialController@update');
$router->post('/editorial-update', 'EditorialController@updateEditorial');
$router->get('/editorial-delete/{id}', 'EditorialController@delete');


$router->get('/adicionar-noticia', 'NewsController@index');
$router->post('/news-create', 'NewsController@create');
$router->get('/news-update/{id}', 'NewsController@update');
$router->post('/news-update', 'NewsController@updateNews');
$router->get('/news-delete/{id}', 'NewsController@delete');
$router->get('/get-news/{id}', 'NewsController@getById');