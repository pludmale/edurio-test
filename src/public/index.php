<?php require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app with config
$settings = require '../app/settings.php';
$app = new \Slim\App(['settings' => $settings]);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';

// Register routes
require __DIR__ . '/../app/routes.php';

// Run application
$app->run();
