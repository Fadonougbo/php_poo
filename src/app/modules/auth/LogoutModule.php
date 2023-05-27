<?php 
namespace App\modules\auth;

use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\router\Router;


class LogoutModule
{

    public function __construct(
            private Router $router,
            private SessionInterface $session
    )
    {
        
        $this->router->map("GET|POST","/logout",[$this,"home"],"blog_logout");
    }

    public function home(ServerRequestInterface $ServerRequest):string|ResponseInterface
    {

        $this->session->deleteSession("userinfo");

        return (new Response())->withHeader("Location","/admin");
    }


}


?>