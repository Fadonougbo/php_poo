<?php 

namespace App\modules;

use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\globalActions\Create;
use Utils\render\Render;
use Utils\router\Router;
use Utils\validation\FormValidator;
use \PDO;
use Utils\RelationCategoriePost;

class CreatePostModule extends Create
{

	protected string $tableName="posts";

	protected bool $addDate=true;

	protected array $validKeys=["name","slug","content"];

	protected array $message=[
		"success"=>"L'article a bien été créé",
		"no_create"=>"L'article n'a pas été créé",
		"invalideForm"=>"Veillez corriger vos erreurs ",
		"invalideCategorie"=>"Veillez selectionné une categorie valide",
		"slugExist"=>"Ce slug exist déja"
	];

	protected $validationStatus=null;

	public function __construct(

		private Router $router,
        private Render $render,
        public PDO $pdo,
		public SessionInterface $session,
		private RelationCategoriePost $relationCategoriePost
	)
	{
		$this->router->map("GET|POST","/admin/create/post",[$this,"index"],"create_post_home");

		parent::__construct($pdo,$session);
	
	}

	public function index(ServerRequestInterface $ServerRequest):string|ResponseInterface
	{

		$categories=$this->relationCategoriePost->getAllCategoriesList();

		$allCategorieId=$this->relationCategoriePost->getAllCategoriesId();

		$createArticle=parent::create($ServerRequest,$allCategorieId);

		if ($createArticle)
		{
			return (new Response())->withHeader("location","/admin");
		}

		return $this->render->show("adminViews/posts/createPostView",parameter:[
															"validationStatus"=>$this->validationStatus??false,
															"categories"=>$categories
														]);
	}

	/**
	 * Validation des données
	 * @param  array  $body $_POST params
	 * @return array|bool     
	 */
	protected function formIsValid(array $body):array|bool
	{
		return (new FormValidator($body))->required("name","content","slug")
										 ->slug("slug")
										 ->lengthMin(["name"=>3,"content"=>5,"slug"=>4])
										 ->validate();
	}

	
}

 ?>