<?php


require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/Settings.php');

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$generator = new \Programster\OrmGenerator\Generator($db, __DIR__ . '/output');
$generator->run();