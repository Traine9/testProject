<?php
$DB = new PDO("mysql:host={$_ENV['MYSQL_HOST']};dbname=test;charset=UTF8", $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);

/**
 * Get connection
 *
 * @return PDO
 */
function getConnection() {
    global $DB;
    return $DB;
}