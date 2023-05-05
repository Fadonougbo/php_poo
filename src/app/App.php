<?php
namespace App;


use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Utils\render\Render;
use Utils\router\Router;
use Utils\validation\FormErrorMessage;

class App implements RequestHandlerInterface
{   
    /**
     * Summary of modulesInstance
     * @var array module list
     */
    //private array $modulesInstance=[];
    
    private array $middlewareList=[];

    private int $index=0;

    private Response $response;

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
         * initialise les modules et sous modules
         */
        $this->initModule();

    }

    /**
     * Instancie les modules et les sous modules
     * 
     * @return void init the sites modules
     */
    private function initModule():void
    {
        foreach($this->moduleList as $module)
        {   
            $moduleInstance=$this->container->get($module);

            if(!empty($moduleInstance->subModuleList))
            {
                foreach($moduleInstance->subModuleList as $subModule)
                {
                    $this->container->get($subModule);
                }
            }

        }

    }

    /**
     * Add middleware
     * @param  MiddlewareInterface $middleware [description]
     * @return self                       [description]
     */
    public function pipe(string $middleware):self
    {
        $this->middlewareList[]=$middleware;

        return $this;
    }

    /**
     * Renvoie un middleware
     * @return [type] [description]
     */
    private function getMiddleware():?MiddlewareInterface
    {
        if(isset($this->middlewareList[$this->index]))
        { 
            return $this->container->get($this->middlewareList[$this->index]);
        }


        return null;
    }


    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware=$this->getMiddleware();

        if(!empty($middleware))
        {
            $this->index++;
            $this->response=$middleware->process($request,$this);

        }

        return $this->response;
        
    }


}

?>