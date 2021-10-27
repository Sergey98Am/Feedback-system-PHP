<?php

use Core\Session;
use Core\Model;
use Core\Router;

require '../vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

Model::createDBIfNotExists();

session_start();

if (!Session::exists('errors')) {
    $errors = Session::setSession('errors', []);
}

if (!Session::exists('postData')) {
    $errors = Session::setSession('postData', []);
}

$router = new Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin\UserManagement']);
$router->add('admin/{controller}/{id:\d+}/{action}', ['namespace' => 'Admin\UserManagement']);

$router->dispatch($_SERVER['QUERY_STRING']);