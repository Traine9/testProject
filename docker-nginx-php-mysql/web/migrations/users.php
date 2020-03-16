<?php

require $dir . '/vendor/autoload.php';
require_once "../bootstrap/database.php";

$query = "CREATE TABLE `users` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`email` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`password` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`image` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
	UNIQUE KEY `index_email` (`email`) USING BTREE,
	PRIMARY KEY (`id`)
);";
$result = $DB->query($query);
if ($result === false) {
    die('migration failed');
}

