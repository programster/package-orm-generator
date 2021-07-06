<?php


require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/Settings.php');


$connString =
    "host=" . DB_HOST
    . " dbname=" . DB_NAME
    . " user=" . DB_USER
    . " password=" . DB_PASSWORD
    . " port=" . DB_PORT
    . " options='--client_encoding=UTF8'";

$db = pg_connect($connString);

if ($db == false)
{
    throw new Exception("Failed to initialize database connection.");
}

$generator = new \Programster\OrmGenerator\PgSqlGenerator($db, __DIR__ . '/output');
$generator->run();