<?php
namespace App\RouterModule;


class Router
{

	public static function create() : \Nette\Application\IRouter
	{
		$router = new \Nette\Application\Routers\RouteList();

		$router[] = new \Nette\Application\Routers\Route(
			'<module>/<presenter>/<action>[/<id>]', [
				'module' => 'StepOne',
				'presenter' => 'Homepage',
				'action' => 'default',
			]
		);

		return $router;
	}

}
