<?php

require $dir . '/vendor/autoload.php';
require "../bootstrap/database.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use \Illuminate\Database\Schema\Blueprint as Blueprint;

Capsule::schema()->create('users', function (Blueprint $table) {

    $table->increments('id');
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('image')->nullable();

    $table->timestamps();
});