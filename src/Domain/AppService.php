<?php

namespace App\Domain;

abstract class AppService
{
	abstract public function find($id);
	abstract public function findAll();
}