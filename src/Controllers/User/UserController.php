<?php

namespace App\Controllers\User;

use App\Domain\User\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator;

class UserController
{
	/**
	 * @var UserService
	 */
	protected UserService $service;

	/**
	 * @param UserService $service
	 */
	public function __construct(UserService $service)
	{
		$this->service = $service;
	}


	public function get(ServerRequestInterface $request, ResponseInterface $response, $params): ResponseInterface
	{

		$user = $this->service->find($params['id']);
		$response->getBody()->write(json_encode(['success' => true, 'data'=>$user]));

		return $response->withHeader('Content-Type', 'application/json');
	}

	public function getAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{
//		$users = User::all();
		$users = $this->service->findAll();
		$response->getBody()->write(json_encode(['success' => true, 'data'=>$users]));

		return $response->withHeader('Content-Type', 'application/json');
	}

}