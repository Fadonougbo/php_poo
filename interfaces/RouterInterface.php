<?php 

namespace Interfaces;

interface RouterInterface
{
	public function map(string $method,string $path,string|array $target,?string $name=null);

	public function generate(string $route_name,?array $params=[],?string $link_content=null):string;

	public function match();
}

?>