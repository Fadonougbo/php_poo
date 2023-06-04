<?php

use GuzzleHttp\Psr7\ServerRequest;
use Interfaces\Auth;
use Utils\database\DB;
use function DI\create;
use Utils\render\Render;
use Utils\router\Router;

use function DI\autowire;
use Utils\session\Session;
use Interfaces\SessionInterface;
use Psr\Http\Message\RequestInterface;
use Utils\auth\Authentification;
use Utils\validation\FormErrorMessage;

return [
    PDO::class=>function(){

        return  DB::getPdoConnection($_ENV["DB_NAME"]);
    },
    Auth::class=>autowire(Authentification::class),
    RequestInterface::class=>function()
    {
        return ServerRequest::fromGlobals();
    },
    FormErrorMessage::class=>autowire(),
    SessionInterface::class=>autowire(Session::class),
    Router::class=>create(),
    Render::class=>create(),
    AltoRouter::class=>create()
]



?>