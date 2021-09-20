<?php

namespace App\Domain\User;

use App\Domain\AppService;

class UserService extends AppService
{
	public function find($id)
	{
		return User::find(intval($id));
	}

	public function findAll()
	{
		return User::all();
	}
}