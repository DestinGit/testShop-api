<?php

namespace App\Controllers\Auth;

use App\Domain\Auth\AuthService;
use App\Exceptions\WrongDataException;
use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
	/**
	 * @var AuthService
	 */
	protected AuthService $service;
	protected Validator $validator;

	public function __construct(AuthService $service, Validator $validator)
	{
		$this->service = $service;
		$this->validator = $validator;
	}

	/**
	 * @throws \Exception
	 */
	public function signIn(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{
		$result = [ 'success' => true ];
		$status = 200;

		// Collect input from the HTTP request
		$data = (array)$request->getParsedBody();

		$auth = $this->service->attempt($data['email'], $data['password']);

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

	/**
	 * @throws \Exception
	 */
	public function signUp(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{
		// Collect input from the HTTP request
		$data = (array)$request->getParsedBody();

		$user = $this->service->create($data);

		$auth = $this->service->attempt($data['email'], $user->id . 'bonjour');

		$response->getBody()->write(json_encode([
			'success' => !empty($user),
			'userId' => $auth['id'],
			'token' => $this->service->getToken()
		]));
		return $response->withHeader('Content-Type', 'application/json')
			->withStatus(200);
	}

	public function signOut($request, $response)
	{
		$this->service->logout();

		$response->getBody()->write(json_encode(['success' => true]));
		return $response->withHeader('Content-Type', 'application/json');
	}
}