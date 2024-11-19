<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;
final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
        $router = new RouteList();

        //API
        $router->addRoute('api/v3/pet', 'Api:Pet:store');
        $router->addRoute('api/v3/pet/schema', 'Api:Pet:schema');
        $router->addRoute('api/v3/pet/findByStatus', 'Api:Pet:indexByStatus');
        $router->addRoute('api/v3/pet/findByTags', 'Api:Pet:indexByTags');
        $router->addRoute('api/v3/pet/<id>', 'Api:Pet:handle');
        $router->addRoute('api/v3/pets', 'Api:Pet:index');
        $router->addRoute('api/v3/categories', 'Api:Category:index');
        $router->addRoute('api/v3/tags', 'Api:Tag:index');
        $router->addRoute('api/v3/statuses', 'Api:Status:index');

        //UI
		$router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}
}
