<?php

use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use Tuupola\Middleware\JwtAuthentication;

$capsule = new Manager();
$capsule->addConnection([
	'driver' 	=> 'sqlite',
	'database' 	=> __DIR__ . '/../database/shopDB.sqlite',
	'prefix'	=> ''
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();


\Illuminate\Database\Capsule\Manager::schema()->drop('users');
\Illuminate\Database\Capsule\Manager::schema()->drop('products');
\Illuminate\Database\Capsule\Manager::schema()->drop('categories');
\Illuminate\Database\Capsule\Manager::schema()->drop('baskets');

return [
		'settings' => function() {
			return require __DIR__ . '/settings.php';
		},

		App::class => function(ContainerInterface $container) {
			AppFactory::setContainer($container);
			return AppFactory::create();
		},

		ResponseFactoryInterface::class => function(ContainerInterface $container) {
			return $container->get(App::class)->getResponseFactory();
		},

		BasePathMiddleware::class => function(ContainerInterface $container) {
			return new BasePathMiddleware($container->get(App::class));
		},

		JwtAuthentication::class => function(ContainerInterface $container): JwtAuthentication {
			return new JwtAuthentication($container->get('settings')['jwt_authentication']);
		},
		ErrorMiddleware::class=>function(ContainerInterface $container) {
			/**
			 * @var $app App
			 */
			$app = $container->get(App::class);
			$settings = $container->get('settings')['error'];

			return new ErrorMiddleware(
				$app->getCallableResolver(),
				$app->getResponseFactory(),
				(bool)$settings['display_error_details'],
				(bool)$settings['log_errors'],
				(bool)$settings['log_error_details']
			);
		},

];