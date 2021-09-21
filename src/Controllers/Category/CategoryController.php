<?php

namespace App\Controllers\Category;

use App\Domain\Auth\AuthService;
use App\Domain\Category\CategoryService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
	/**
	 * @var CategoryService
	 */
	protected CategoryService $service;
	/**
	 * @var AuthService
	 */
	protected AuthService $authService;

	/**
	 * @param CategoryService $service
	 * @param AuthService $authService
	 */
	public function __construct(CategoryService $service, AuthService $authService)
	{
		$this->service = $service;
		$this->authService = $authService;
	}


	public function getAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{
		$categories = $this->authService->check() ?
			$this->service->findAll() :
		$this->service->findOnlyForPublic();

		$response->getBody()->write(json_encode(['success' => true, 'data'=>$categories]));
		return $response->withHeader('Content-Type', 'application/json');
	}
}