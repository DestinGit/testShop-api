<?php

namespace App\Controllers\Category;

use App\Domain\Category\CategoryService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CategoryController
{
	/**
	 * @var CategoryService
	 */
	protected $service;

	/**
	 * @param CategoryService $service
	 */
	public function __construct(CategoryService $service)
	{
		$this->service = $service;
	}


	public function getAll(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
	{
		$categoies = $this->service->findAll();
		$response->getBody()->write(json_encode(['success' => true, 'data'=>$categoies]));
		return $response->withHeader('Content-Type', 'application/json');
	}
}