<?php

use App\Providers\ServiceProvider;
use DI\ContainerBuilder;
use Slim\App;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
// Set up settings
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Create App instance
/**
 * @var App
 */
$app = $container->get(App::class);

//$_SERVER['app'] = &$app;

// Laod service providers
ServiceProvider::setup($app, [
	\App\Providers\DatabaseServiceProvider::class
]);

// Register routes
(require __DIR__ . '/../config/routes.php')($app);

// Register middleware
(require  __DIR__ . '/../config/middlewares.php')($app);

return $app;

