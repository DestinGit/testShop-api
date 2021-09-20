<?php

if (! function_exists('app')) {
	/**
	 * @return \Slim\App
	 */
	function app (): \Slim\App
	{
		return $_SERVER['app'];
	}
}

if (! function_exists('base_path')) {
	function base_path($path = ''): string
	{
		return __DIR__ . "/../{$path}";
	}
}

if (! function_exists('config_path')) {
	function config_path($path = ''): string
	{
		return base_path("config/{$path}");
	}
}

if (! function_exists('database_path')) {
	function database_path($path = ''): string
	{
		return base_path("database/{$path}");
	}
}

if (!function_exists('throw_when'))
{
	function throw_when(bool $fails, string $message, string $exception = Exception::class)
	{
		if (!$fails) return;

		throw new $exception($message);
	}
}