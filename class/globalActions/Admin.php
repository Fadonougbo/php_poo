<?php 

namespace Utils\globalActions;

use Psr\Http\Message\RequestInterface;
use Utils\render\Render;
use Utils\router\Router;
use \PDO;


/**
 * Classe parent pour les parties admin
 */
class Admin extends PostsPaginate
{

	protected string $urlName;

	protected string $baseUrl;

	public function __construct(
		private Router $router,
        private Render $render,
        public PDO $pdo,
        protected RequestInterface $serverRequest
	)
	{
        parent::__construct($pdo,$serverRequest);

        $this->router->map("GET",$this->baseUrl,[$this,"home"],$this->urlName);
	}

	
}



?>