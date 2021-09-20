<?php

namespace App\Middleware;

use App\Domain\Auth\AuthService;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Slim\Psr7\Response;

class AuthMiddleware
{
	public function __invoke(Request $request, Handle $handle)
	{
		$response = $handle->handle($request);
		$existing_body = (string)$response->getBody();
		$response = new Response();

		if (! (new AuthService())->check()) {
			$response = $response->withStatus(403);
			$response->getBody()->write(json_encode(['success'=> false, 'message'=>'Access denied. Sign in before doing that']));

			return $response->withHeader('Content-Type', 'application/json');
		}
		$response->getBody()->write($existing_body);
		return $response;
	}
}