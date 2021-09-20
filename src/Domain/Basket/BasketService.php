<?php

namespace App\Domain\Basket;


class BasketService
{


	public function find($id)
	{
		return Basket::where('user_id', intval($id));
	}

	public function addToBasket($userId, $product, $quantity)
	{
		$basket = Basket::where('user_id', $userId)
				->where('product_id', $product);
		if (! $basket) {
			Basket::create([
				'user_id' => $userId,
				'product_id' => $product,
				'quantity' => $quantity
			]);
		} else {
			$qty = $basket->quantity + $quantity;
			Basket::where('user_id', $userId)
				->where('product_id', $product)
			->update(['quantity' => $qty]);
		}

		return $this->find($userId);
	}
}