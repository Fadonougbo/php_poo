<?php 

namespace App\modules;

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
use Utils\RelationCategoriePost;

class UpdatePostModule extends Update
{

	private $params;

	private $id;

	protected string $tableName="posts";

	protected string $baseUrl="/admin/update/post/[*:slug]_[i:id]";

	protected string $urlName="update_post_home";

	protected $validationStatus=null;

	protected array $valideArrayKeys=["name","slug","content","updated_at","image_visibility"];

	protected bool $isUpdated=false;

	protected array $messageList=[
		"success"=>"L'article à bien été modifié",
		"no_update"=>"Article non modifié",
		"invalideForm"=>"Veillez corriger vos erreurs",
		"invalideCategorieSelection"=>"Veillez sélectionné une categorie valide",
		"slugExist"=>"Ce slug exist déja",
		"invalideImage"=>"L'image n'est pas valide"
	];


	public function __construct(

		private Router $router,
        private Render $render,
        public PDO $pdo,
		public SessionInterface $session,
		public RelationCategoriePost $relationCategoriePost
	)
	{
		parent::__construct($router,$pdo,$session);

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


		$post=parent::fetchCurrentElement($this->tableName,$this->id);

		if (!$post)
		{
			return Helper::badIdRedirect("/admin");
		}


		$categories_post=$this->relationCategoriePost->getCurrentCategoriesInfo($post);

		$categorieNameList=$this->relationCategoriePost->getCurrentCategorieName($post->id,$categories_post);

		$categories=$this->relationCategoriePost->getAllCategoriesList();

		$categorieIdLIst=$this->relationCategoriePost->getAllCategoriesId();

		$updatePost=parent::update($ServerRequest,$post,$categorieIdLIst);


		if ($updatePost)
		{
			return (new Response())->withStatus(301)->withHeader("Location","/admin?p=$paginatePosition");
		}
		
		return $this->render->show("adminViews/posts/updatePostView",parameter:[
																"post"=>$post,
																"categories"=>$categories,
																"categorieNameList"=>$categorieNameList,
																"categories_post"=>$categories_post,
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

		return (new FormValidator($body))->required("name","content","slug","updated_at")
										 ->lengthMin(["name"=>3,"content"=>5,"slug"=>4])
										 ->slug("slug")
										 ->dateFormat(["updated_at"=>"Y-m-d H:i:s"])
										 ->validate() ;
	}


}

 ?>