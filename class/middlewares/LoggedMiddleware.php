<?php
namespace Utils\middlewares;

use Interfaces\Auth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Utils\errors\ForbiddenException;

class LoggedMiddleware implements MiddlewareInterface
{

    public function __construct(private Auth $auth)
    {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        $user=$this->auth->getUser();

        if(is_null($user))
        {
            throw new ForbiddenException("Utilisateur invalide");
        }

        return $handler->handle($request->withAttribute("user",$user));
    }
}


?>