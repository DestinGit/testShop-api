<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;

class PathNotFound
{
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler)
	{
		try {
			return $handler->handle($request);
		} catch (HttpNotFoundException $httpException) {
			$response = (new Response())->withStatus(404);
			$response->getBody()->write(json_encode(['success'=> false, 'error'=>'404 Not found']));

			return $response->withHeader('Content-Type', 'application/json');
		}
	}
}