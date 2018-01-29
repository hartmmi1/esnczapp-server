<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

		// api
		$api = new Route('api/<action>[/<id>]', [
			'module' => 'Api',
			'presenter' => 'Api',
			'action' => 'default',
		]);
		$router[] = $api;

		// administration
		$admin = new Route('admin/<presenter>/<action>[/<id>]', [
			'module' => 'Admin',
			'presenter' => 'Dashboard',
			'action' => 'default',
		]);
		$router[] = $admin;

		// public
		$default = new Route('<presenter>/<action>[/<id>]', [
			'module' => 'Front',
			'presenter' => 'Landing',
			'action' => 'default',
		]);
		$router[] = $default;
		return $router;
	}

}
