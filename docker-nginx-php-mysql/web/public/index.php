<?php


/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use \App\Models\Users;

Users::createTable();

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);

# Toggle language
$router->add('auth', ['controller' => 'Auth', 'action' => 'index']);
$router->add('auth/activate', ['controller' => 'Auth', 'action' => 'activate']);
$router->add('auth/logout', ['controller' => 'Auth', 'action' => 'logout']);
$router->add('language', ['controller' => 'Language', 'action' => 'change']);


# English
//$router->add('en/auth', ['controller' => 'Auth', 'action' => 'index']);
//$router->add('en/auth/activate', ['controller' => 'Auth', 'action' => 'activate']);
//$router->add('en/auth/logout', ['controller' => 'Auth', 'action' => 'logout']);
//
//# Russian
//$router->add('ru/auth', ['controller' => 'Auth', 'action' => 'index']);
//$router->add('ru/auth/activate', ['controller' => 'Auth', 'action' => 'activate']);
//$router->add('ru/auth/logout', ['controller' => 'Auth', 'action' => 'logout']);

$router->dispatch($_SERVER['QUERY_STRING']);
