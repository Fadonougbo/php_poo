<?php 

namespace App\modules\catgories_admin;

use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\Helper;
use Utils\globalActions\Update;
use Utils\render\Render;
use Utils\router\Router;
use Utils\validation\FormValidator;
use \PDO;

class UpdateCategorieModule extends Update
{
	private $params;

	private $id;

	protected string $tableName="categories";

	protected string $baseUrl="/admin/update/category/[*:slug]_[i:id]";

	protected string $urlName="update_categorie_home";

	protected $validationStatus=null;

	protected array $valideArrayKeys=["name","slug"];

	protected bool $isUpdated=false;

	protected array $messageList=[
		"success"=>"La categorie à bien été modifié",
		"no_update"=>"categorie non modifié",
		"invalideForm"=>"Veillez corriger vos erreurs"
	];

	public function __construct(

		private Router $router,
        private Render $render,
        public PDO $pdo,
		public SessionInterface $session
	)
	{

		parent::__construct($router,$session);

		$match=$this->router->match();

		if (isset($match["params"]))
		{
			$this->params=$match["params"];
		}

		if (isset($this->params["id"]))
		{
			$this->id=(int)$this->params["id"];
		}	
	}

	public function index(ServerRequestInterface $ServerRequest):string|ResponseInterface
	{
		/**
		 * Conservation de la position du paramettre p
		 * 
		 */
		$pos=!empty($ServerRequest->getQueryParams())?(int)($ServerRequest->getQueryParams()["pos"]):1;

		$paginatePosition=!is_int($pos)?1:$pos;


		$post=$this->fetchCurrentPost($this->id);

		if (!$post)
		{
			return Helper::badIdRedirect("/admin/categories");
		}

		$updateCategorie=parent::update($ServerRequest,$post);

		if ($updateCategorie)
		{
			return (new Response())->withStatus(301)->withHeader("Location","/admin/categories?p=$paginatePosition");
		}

		
		return $this->render->show("adminViews/categories/updateCategorieView",parameter:[
																							  "post"=>$post,
																		                      "validationStatus"=>$this->validationStatus??false
																                          ]);
	}

	/**
	 * Validation des données
	 * @param  array  $body $_POST params
	 * @return array|bool     
	 */
	public function formIsValid(array $body):array|bool
	{

		return (new FormValidator($body))->required("name","slug")
										 ->lengthMin(["name"=>3,"slug"=>4])
										 ->slug("slug")
										 ->validate() ;
	}
}


?>