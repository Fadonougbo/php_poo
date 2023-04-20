<?php 

namespace Utils\globalActions;

use Utils\router\Router;
use \PDO;


/**
 * Classe parent pour les parties admin
 */
class Admin extends GlobaleAction
{

	protected string $urlName;

	protected string $baseUrl;

	public function __construct(
		protected Router $router,
        public PDO $pdo
	)
	{

        $this->router->map("GET",$this->baseUrl,[$this,"home"],$this->urlName);

        parent::__construct($pdo);
	}

	
}



?>