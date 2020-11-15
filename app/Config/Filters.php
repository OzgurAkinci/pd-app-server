<?php namespace Config;

use App\Filters\AuthFilter;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'       => CSRF::class,
		'toolbar'    => DebugToolbar::class,
		'honeypot'   => \CodeIgniter\Filters\Honeypot::class,
		'authFilter' => AuthFilter::class,
	];

	// Always applied before every request
	public $globals = [
		'before' => [
		],
		'after'  => [
			'toolbar',
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [
		'authFilter' => [
			'before' => [
				'api/user/*',
				'api/user',
				'api/product/*',
				'api/product',
				'api/user/*',
				'api/user'
			],
		],
	];
}
