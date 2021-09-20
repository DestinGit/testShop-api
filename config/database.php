<?php
return [
	'connections' => [
		'sqlite' => [
			'driver' => 'sqlite',
//			'url' => database_path('shopDB.sqlite'),
			'url' => realpath(__DIR__ . '/../database/shopDB.sqlite'),
			'prefix' => '',
		]
	]
];
