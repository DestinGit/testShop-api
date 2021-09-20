<?php

namespace App\Providers;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

class DatabaseServiceProvider extends ServiceProvider
{

	public function register()
	{
		$options = $this->resolve('settings')['db'];
		$capsule = new Manager();
		$capsule->addConnection($options);
		$capsule->setAsGlobal();
		$capsule->bootEloquent();

		$this->bind('db', fn() => $capsule);
	}

	public function boot()
	{
		$this->dbSchema();
	}

	private function dbSchema() {
		/**
		 * @var $capsule Illuminate\Database\Capsule\Manager
		 */
		$capsule = $this->resolve('db');

		if (! $capsule::schema()->hasTable('users')) {
			$capsule::schema()->create('users', function ($table) {
				$table->increments('id');
				$table->string('email')->unique();
				$table->string('name');
				$table->string('password');
				$table->string('updated_at');
				$table->string('created_at');
//	$table->timestamps();
			});
		}

		if (! $capsule::schema()->hasTable('products')) {
			$capsule::schema()->create('products', function (Blueprint $table) {
				$table->increments('id');
				$table->string('label');
				$table->string('description');
				$table->float('price');
				$table->integer('category_id');
				$table->string('thumbnail_url');
				$table->boolean('visible_public');
				$table->boolean('visible_authenticated');
			});
		}

		if (! $capsule::schema()->hasTable('categories')) {
			$capsule::schema()->create('categories', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('index');
				$table->string('label');
				$table->string('description');
			});
		}

		if (! $capsule::schema()->hasTable('baskets')) {
			$capsule::schema()->create('baskets', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('user_id');
				$table->integer('product_id');
				$table->integer('quantity');
			});
		}
	}
}