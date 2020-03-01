<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    "driver" => "mysql",
    "host" => $_ENV['MYSQL_HOST'],
    "database" => 'test',
    "username" => $_ENV['MYSQL_USER'],
    "password" => $_ENV['MYSQL_PASSWORD']
]);

//Make this Capsule instance available globally.
$capsule->setAsGlobal();

// Setup the Eloquent ORM.
$capsule->bootEloquent();
