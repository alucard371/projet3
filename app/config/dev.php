<?php

// Doctrine (db)

$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'charset' => 'utf8',
    'host' => '127.0.0.1',
    'port' => '3306',
    'dbname' => 'projet3',
    'user' => 'projet3_user',
    'password' => 'secret',
);

//enable debug
$app['debug'] = true;

//define log level
$app['monolog.level'] = 'INFO';