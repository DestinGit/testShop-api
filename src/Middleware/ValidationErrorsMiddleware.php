<?php

namespace App\Middleware;

use App\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handle;
use Respect\Validation\Validator as v;
use Slim\Psr7\Response;

class ValidationErrorsMiddleware
{
	public function __invoke(Request $request, Handle $handle)
	{
		$validator = new Validator();

		// Collect input from the HTTP request
		$data = (array)$request->getParsedBody();

		$validate = $validator->validate($data, [
			'name' => v::notEmpty(),
			'email' => v::noWhitespace()->notEmpty(),
			'password' => v::noWhitespace()->notEmpty()
		]);

		if ($validate->failed()) {
			$response = new Response();
			$response->getBody()->write(json_encode([
				'success' => false,
				'error' => $validate->getErrors()
			]));
			return $response->withHeader('Content-Type', 'application/json');
		}

		return $handle->handle($request);
	}
}