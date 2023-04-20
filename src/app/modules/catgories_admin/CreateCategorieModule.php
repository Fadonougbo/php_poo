<?php 

namespace App\modules\catgories_admin;

use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\globalActions\Create;
use Utils\render\Render;
use Utils\router\Router;
use Utils\validation\FormValidator;
use \PDO;

class CreateCategorieModule extends Create
{
	protected string $tableName="categories";

	protected bool $addDate=false;

	protected array $validKeys=["name","slug"];

	protected array $message=[
		"success"=>"la categorie à bien été créé",
		"no_create"=>"Categorie non créé",
		"invalideForm"=>"Veillez corriger vos erreurs",
		"slugExist"=>"Ce slug exist déja"
	];

	protected $validationStatus=null;

	public function __construct(

		private Router $router,
        private Render $render,
        public PDO $pdo,
		public SessionInterface $session
	)
	{
		$this->router->map("GET|POST","/admin/create/categorie",[$this,"index"],"create_categorie_home");

		parent::__construct($pdo,$session);
	
	}

	public function index(ServerRequestInterface $ServerRequest):string|ResponseInterface
	{

		$createCategorie=parent::create($ServerRequest);

		if($createCategorie)
		{
			return (new Response())->withHeader("location","/admin/categories");
		}
		

		return $this->render->show("adminViews/categories/createCategorieView",parameter:["validationStatus"=>$this->validationStatus??false]);
	}

	/**
	 * Validation des données
	 * @param  array  $body $_POST params
	 * @return array|bool     
	 */
	public function formIsValid(array $body):array|bool
	{

		return (new FormValidator($body))->required("name","slug")
										 ->slug("slug")
										 ->lengthMin(["name"=>3,"slug"=>4])
										 ->validate() ;

	}
}


?>