<?php 

namespace Utils\middlewares;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SlashUrlRedirect implements MiddlewareInterface
{

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{	


		$uri=$request->getUri()->getPath();

		if($uri[-1]==="/" && $uri!=="/")
      {
          $newUri=substr($uri,0,-1);
          $response=(new Response())
                    ->withStatus(301)
                    ->withHeader("Location",$newUri);
          return $response;
      }


      return $handler->handle($request);

	}
}



?>