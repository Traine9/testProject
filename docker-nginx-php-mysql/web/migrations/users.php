<?php

require $dir . '/vendor/autoload.php';
require "../bootstrap/database.php";

$query = "CREATE TABLE `users` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(512) NOT NULL,
	`email` VARCHAR(512) NOT NULL,
	`password` VARCHAR(512) NOT NULL,
	`image` VARCHAR(512) DEFAULT '',
	UNIQUE KEY `index_email` (`email`) USING BTREE,
	PRIMARY KEY (`id`)
);";
$result = $DB->query($query);
if ($result === false) {
    die('migration failed');
}

