<?php

namespace App\Providers;

use Psr\Container\ContainerInterface;
use Slim\App;

abstract class ServiceProvider
{
	/**
	 * @var App
	 */
	protected $app;
	/**
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * @param App $app
	 */
	final public function __construct(App $app)
	{
		$this->app = $app;
		$this->container = $this->app->getContainer();
	}

	final public static function setup(App $app, array $providers)
	{
		$providers = array_map(fn($provider) => new $provider($app), $providers);
		array_walk($providers, fn(ServiceProvider $provider) => $provider->register());
		array_walk($providers, fn(ServiceProvider $provider) => $provider->boot());
	}

	public function bind(string $key, callable $resolvable)
	{
		$this->container->set($key, $resolvable);
	}

	public function resolve(string $key)
	{
		return $this->container->get($key);
	}

	abstract public function register();
	abstract public function boot();
}