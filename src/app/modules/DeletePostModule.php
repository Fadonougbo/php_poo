<?php 

namespace App\modules;

use Interfaces\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\globalActions\Delete;
use Utils\render\Render;
use Utils\router\Router;
use \PDO;

class DeletePostModule extends Delete
{

	private $params;

	protected int $id;

	protected string $tableName="posts";

	protected string $baseUrl="/admin";

	protected array $messageList=[

		"success"=>"L'article a bien été supprimé",
		"no_success"=>"L'article n'a pa été supprimé"
	];

	public function __construct(

		private Router $router,
        private Render $render,
        public SessionInterface $session,
        protected PDO $pdo
	)
	{
		/**
		 * Route
		 */
		$this->router->map("POST","/admin/delete/post_[i:id]",[$this,"index"],"delete_post_home");

		/**
		 * Constructeur parente
		 */
		parent::__construct($pdo,$session);

		if (isset($this->router->match()["params"]))
		{
			$this->params=$this->router->match()["params"];
		}

		if (isset($this->params["id"]))
		{
			$this->id=(int)$this->params["id"];
		}	
	}

	public function index(ServerRequestInterface $ServerRequest)
	{

	  return parent::delete($ServerRequest);

	}


}


?>