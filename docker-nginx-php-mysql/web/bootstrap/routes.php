<?php
/**
 * Routing
 */
$router = \Core\Router::Get();

// Add the routes
$router->add('/', ['controller' => 'Home', 'action' => 'index']);
# Toggle language
$router->add('/auth', ['controller' => 'Auth', 'action' => 'index']);
$router->add('/auth/logout', ['controller' => 'Auth', 'action' => 'logout']);
$router->add('/language', ['controller' => 'Language', 'action' => 'change']);
