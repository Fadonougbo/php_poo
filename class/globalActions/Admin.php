<?php 

namespace Utils\globalActions;

use Interfaces\SessionInterface;
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
        public PDO $pdo,
	)
	{

		parent::__construct($pdo);

		$this->router->map("GET",$this->baseUrl,[$this,"home"],$this->urlName);

	}

	
}



?>