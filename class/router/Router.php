<?php 

namespace Utils\router;

use Interfaces\RouterInterface;

use \AltoRouter;

class Router implements RouterInterface
{
	private AltoRouter $altoRouter;

	public function __construct()
	{
		$this->altoRouter=new AltoRouter();
	}

	public function map(string $method,string $path,string|array $target,?string $name=null)
	{
		$this->altoRouter->map($method,$path,$target,$name);
	}

	public function generate(string $route_name,?array $params=[],?string $link_content=null): string
	{
		$routeNames=$this->getRouteNameList();

		if (in_array($route_name,$routeNames))
		{
			$link=$this->altoRouter->generate($route_name,$params);

			if (empty($link_content))
			{
				return $link;
			}else 
			{
				return <<<HTML

						<a href="$link">$link_content</a>
				HTML;
			}

		}else 
		{
				if (empty($link_content))
				{
					return "#";
				}else 
				{
					return <<<HTML

							<a href="#">$link_content</a>
					HTML;
				}
		}

		
	}


	public function match():bool|array
	{
		return $this->altoRouter->match();
	}

	/**
	 * Nom de toutes les routes disponibles
	 * @return [type] [description]
	 */
	public function getRouteNameList()
	{
		$routesList=$this->altoRouter->getRoutes();

		$routeNameList=[];

		foreach ($routesList as $value)
		{
			array_push($routeNameList,$value[3]);
		}

		return $routeNameList;
	}
}

?>