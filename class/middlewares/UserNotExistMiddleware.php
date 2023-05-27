<?php 

namespace Utils\middlewares;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Utils\errors\UserNotExistException;
use Utils\router\Router;

class UserNotExistMiddleware implements MiddlewareInterface
{


	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{	

      try 
      {
          return $handler->handle($request);
      }catch(UserNotExistException $error)
      {
        dump("okok");
        return (new Response())->withHeader("Location","/home");
      }
	}
}



?>