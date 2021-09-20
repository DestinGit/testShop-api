<?php

namespace App\Controllers\Basket;

use App\Domain\Basket\BasketService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BasketController
{
	/**
	 * @var BasketService
	 */
	protected $service;

	/**
	 * @param BasketService $service
	 */
	public function __construct(BasketService $service)
	{
		$this->service = $service;
	}

	public function get(ServerRequestInterface $request, ResponseInterface $response)
	{
		$basket = $this->service->find($_SESSION['user']);

		$response->getBody()->write(json_encode(['success' => true, 'data'=>$basket]));
		return $response->withHeader('Content-Type', 'application/json');
	}
}