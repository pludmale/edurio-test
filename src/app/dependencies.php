<?php
// Dependency Injection Container configuration
$container = $app->getContainer();

// Set up a database connection
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    try {
        $pdo = new PDO('mysql:host=' . $db['host'] .
            ';port=' . $db['port'] .
            ';dbname=' . $db['dbname'],
            $db['user'],
            $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo($e->getMessage());
    }
    return $pdo;
};