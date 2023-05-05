<?php 

namespace Utils\middlewares;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Utils\router\Router;

class RunMiddleware implements MiddlewareInterface
{

  public function __construct(private Router $router)
  {

  }

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{	


		    $match=$this->router->match();

        if(!$match)
        {

          return $handler->handle($request);
        }

        $response=call_user_func_array($match['target'],[$request]);

        if (!is_string($response))
        {
            return $response;
        }

        return new Response(200,[],$response);

	 }
}



?>