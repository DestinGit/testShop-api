<?php

use App\Controllers\Auth\AuthController;
use App\Controllers\Category\CategoryController;
use App\Controllers\User\UserController;
use App\Middleware\ValidationErrorsMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {
	$app->get('/categories', [CategoryController::class, 'getAll']);

	$app->group('/auth', function (RouteCollectorProxy $group) {
		$group->post('/login', [AuthController::class, 'signIn']);
		$group->post('/signup', [AuthController::class, 'signUp'])
			->add(new \App\Middleware\UserWithThatEmailAlreadyExistsMiddleware());
	})->add(new ValidationErrorsMiddleware());

	$app->group('/user', function (RouteCollectorProxy $group) {
		$group->get('/{id:[0-9]+}', [UserController::class, 'get']);
		$group->get('/all', [UserController::class, 'getAll']);
		$group->get('/logout', [AuthController::class, 'signOut']);
	})
		->add($app->getContainer()->get(JwtAuthentication::class));
//		->addMiddleware($app->getContainer()->get(JwtAuthentication::class));
//		->add(new \App\Middleware\AuthMiddleware());
};