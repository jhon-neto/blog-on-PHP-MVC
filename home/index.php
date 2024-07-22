<?php

date_default_timezone_set('America/Sao_Paulo');
ini_set('display_errors', 0);
error_reporting(E_ALL);
session_start();

require '../vendor/autoload.php';
require '../src/routes.php';

$router->run( $router->routes );