<?php 

namespace App\modules\catgories_admin;


use Interfaces\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\globalActions\Delete;
use Utils\render\Render;
use Utils\router\Router;
use \PDO;

class DeleteCategorieModule extends Delete
{
	private $params;

	protected int $id;

	protected string $baseUrl="/admin/categories";

	protected array $messageList=[

		"success"=>"La categorie a bien été supprimé",
		"no_success"=>"La categorie n'a pa été supprimé"
	];

	protected string $tableName="categories";

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
		$this->router->map("POST","/admin/delete/categorie_[i:id]",[$this,"index"],"delete_categorie_home");

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
		return $this->delete($ServerRequest);
	}
}

?>