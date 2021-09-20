<?php

use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return function (App $app) {
	// Parse json, form data and xml
	$app->addBodyParsingMiddleware();

	// Add the Slim built-in routing middleware
	$app->addRoutingMiddleware();

	// Detects and sets the base path into the Slim app instance
	$app->add(BasePathMiddleware::class);

	// HttpNotFoundException
	$app->add(\App\Middleware\PathNotFound::class);

	// Catch exceptions and errors
	$app->add(ErrorMiddleware::class);
};