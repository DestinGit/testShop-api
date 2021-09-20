<?php

use App\Controllers\Auth\AuthController;
use App\Controllers\User\UserController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {
	$app->post('/auth', [AuthController::class, 'signIn']);
	$app->post('/signup', [AuthController::class, 'signUp']);

	$app->group('/user', function (RouteCollectorProxy $group) {
		$group->get('/{id:[0-9]+}', [UserController::class, 'get']);
		$group->get('/all', [UserController::class, 'getAll']);
		$group->get('/logout', [AuthController::class, 'signOut']);
	})
		->addMiddleware($app->getContainer()->get(JwtAuthentication::class));
//		->add(new \App\Middleware\AuthMiddleware());
};