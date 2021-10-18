<?php

use Core\Session;

require '../vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();
//var_dump($_SESSION);
if (!Session::exists('errors')) {
    $errors = Session::setSession('errors', []);
//    var_dump($_SESSION);
}

$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin\UserManagement']);
$router->add('admin/{controller}/{id:\d+}/{action}', ['namespace' => 'Admin\UserManagement']);

$router->dispatch($_SERVER['QUERY_STRING']);