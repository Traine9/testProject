<?php
session_start();

$dir = dirname(__DIR__);
//include composer
require $dir . '/vendor/autoload.php';
//connect database
require $dir . '/bootstrap/database.php';
//make routes
require $dir . '/bootstrap/routes.php';

//@TODO make migrations with command like artiisan
//require __DIR__.'/../migrations/users.php';

//Error and Exception handling
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

\Core\Router::Get()->dispatch($_SERVER['REQUEST_URI']);