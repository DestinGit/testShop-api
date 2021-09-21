<?php

namespace App\Middleware;

use App\Domain\Auth\AuthService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Slim\Psr7\Response;

class UserWithThatEmailAlreadyExistsMiddleware
{
	public function __invoke(Request $request, Handle $handle)
	{
		// Collect input from the HTTP request
		$data = (array)$request->getParsedBody();
		if ((new AuthService())->exist($data['email'])) {
			$response = new Response();
			$response->getBody()->write(json_encode([
				'success' => false,
				'error' => "User with email : '{$data['email']}' already exist"
			]));
			return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
		}

		return $handle->handle($request);
	}
}