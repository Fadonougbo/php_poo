<?php
namespace App;

use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;
use Utils\Helper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\render\Render;
use Utils\router\Router;
use Utils\validation\FormErrorMessage;

class App
{   
    /**
     * Summary of modulesInstance
     * @var array module list
     */
    private array $modulesInstance=[];

    private Router $router;

    public Render $render;

    public SessionInterface $session;

    public FormErrorMessage $errorMessage;

    public function __construct(

        public ContainerInterface $container,
        public array $moduleList
    )
    {
        $this->router=$container->get(Router::class);

        $this->render=$container->get(Render::class);

        $this->session=$container->get(SessionInterface::class);
        
        $this->errorMessage=$container->get(FormErrorMessage::class);

        $this->render->addGlobale(["router"=>$this->router,
                                   "session"=>$this->session,
                                   "errorMessage"=>$this->errorMessage
                                ]);

        /**
         * initialise les module
         */
        $this->initModule();

    }

    /**
     * Instancie les modules
     * @return void init the sites modules
     */
    private function initModule()
    {
        foreach($this->moduleList as $module)
        {   
            $this->modulesInstance[]=$this->container->get($module);
        }
    }


    public function run(ServerRequestInterface $serverInfo):ResponseInterface
    {   
        
        /* Redirect */
        $uri=$serverInfo->getUri()->getPath();
        
        Helper::urlRedirect($uri);


        $match=$this->router->match();

        if (!$match)
        {
           return new Response(404,[],"<h1>Error 404</h1>");
        }

        $response=call_user_func_array($match['target'],[$serverInfo]);

        if (!is_string($response))
        {
            return $response;
        }

        return new Response(200,[],$response);
    }


}

?>