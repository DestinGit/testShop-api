<?php

namespace App\Controllers\Auth;

use App\Domain\Auth\AuthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
	/**
	 * @var AuthService
	 */
	protected AuthService $service;

	public function __construct(AuthService $service)
	{
		$this->service = $service;
	}

	public function signIn(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{
		$result = [ 'success' => true ];
		$status = 200;

		// Collect input from the HTTP request
		$data = (array)$request->getParsedBody();

		$auth = $this->service->attempt($data['email'], 'bonjour');

		if (!$auth) {
			$result['message'] = 'Bad Credentials';
			$result['success'] = false;
			$status = 401;
		} else {
			$result['token'] = $this->service->getToken();
			$result['userId'] = $auth['id'];
		}
		$response->getBody()->write(json_encode($result));
		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($status);
	}

	public function signUp(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{
		// Collect input from the HTTP request
		$data = (array)$request->getParsedBody();

		$user = $this->service->create($data);

		$this->service->attempt($data['email'], $user->password);
//		(new AuthService())->attempt($data['email'], $user->password);

		$response->getBody()->write(json_encode([
			'success' => !empty($user)
		]));
		return $response->withHeader('Content-Type', 'application/json');
	}

	public function signOut($request, $response)
	{
		$this->service->logout();

		$response->getBody()->write(json_encode(['success' => true]));
		return $response->withHeader('Content-Type', 'application/json');
	}
}