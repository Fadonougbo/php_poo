<?php 

namespace Utils\middlewares;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Utils\router\Router;

class NotFoundMiddleware implements MiddlewareInterface
{

  public function __construct(private Router $router)
  {

  }

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{	

      return new Response(404,[],"<h1>Error 404</h1>");
	}
}



?>