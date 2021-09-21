<?php

namespace App\Domain\Category;

use App\Domain\AppService;

class CategoryService extends AppService
{

	public function find($id)
	{
		// TODO: Implement find() method.
	}

	public function findOnlyForPublic()
	{
		return Category::where('visible_public', true);
	}

	public function findAll()
	{
		return Category::all();
	}
}