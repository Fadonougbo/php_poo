<?php 

namespace Utils\globalActions;

use Interfaces\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\Helper;
use Utils\validation\FormValidator;
use \PDO;

class Create extends GlobaleAction
{

	protected string $tableName;

	protected bool $addDate=false;

	protected array $validKeys;

	protected array $message;

	protected $validationStatus;

	protected bool $isCreated=false;

	public function __construct(

		protected PDO $pdo,
		protected SessionInterface $session
	)
	{
		parent::__construct($pdo);
	}

	protected function create(ServerRequestInterface $ServerRequest,?array $valideCategorieIdList=null):bool
	{
		if ($ServerRequest->getMethod()==="POST")
		{
			$parsedBody=$ServerRequest->getParsedBody();

			if (!empty($parsedBody))
			{
				$this->validationStatus=$this->formIsValid($parsedBody);

				if (!is_array($this->validationStatus))
				{	
					$this->runCreation($ServerRequest,$parsedBody,$valideCategorieIdList);

				}else 
				{
					$this->session->setSession("invalideForm",$this->message["invalideForm"]);
				}

			}
		}

		return $this->isCreated;
	}

	protected function formIsValid(array $body)
	{

		return new FormValidator($body);

	}


	/**
	 * Lance la phase de creation
	 * @param  ServerRequestInterface $ServerRequest         [description]
	 * @param  array  $parsedBody            [description]
	 * @param  array|null $valideCategorieIdList list des id valide pour les categories
	 * @return void
	 */
	private function runCreation($ServerRequest,array $parsedBody,$valideCategorieIdList)
	{
		//creation d'element si il n'y a pas de categorie ou si on n'est dans le cas de la creation d'un article 
		if (empty($valideCategorieIdList) || !isset($parsedBody["categories_lists"]) )
		{
			$this->isCreated=$this->createElement($ServerRequest->getParsedBody(),$this->validKeys);

		}else 
		{
			//creation de post avec les categories
			$this->isCreated=$this->createPostWithCategorie($ServerRequest,$parsedBody["categories_lists"],$valideCategorieIdList);
		}


		if ($this->isCreated) 
		{
			$this->session->setSession("success",$this->message["success"]);
		}else 
		{
			$this->session->setSession("no_create",$this->message["no_create"]);
		}

	}

	/**
	 * Insert un article|categorie dans la DB
	 * @param  array  $parsedBody tableau des elements envoyés par l'utilisateur ($_POST)
	 * @param  array  $validKeys  tableau contenant les champs valides
	 * @return [type]             [description]
	 */
	public function createElement(array $parsedBody,array $validKeys)
	{
		
		/**
		 * Verifie si params possede les clés ["name","slug","content"]
		 * @var [type]
		 */
		$params_purged=Helper::purgeArray($parsedBody,$validKeys,true);

		if ($this->addDate)
		{
			$params_purged["created_at"]=date("Y-m-d H:i:s");
			$params_purged["updated_at"]=date("Y-m-d H:i:s");
		}

		$slugExist=parent::slugExistVerification($this->tableName,$params_purged['slug']);

		if ($slugExist)
		{
			$this->session->setSession("invalideForm",$this->message["slugExist"]);

			return false;
		}
		
		$sqlEchapString=Helper::generateInsertEchapString(array_keys($params_purged));

		$implode_key=implode(",",array_keys($params_purged));

		$req=$this->pdo->prepare("INSERT INTO  {$this->tableName} ($implode_key) VALUES($sqlEchapString) ");

		return $req->execute($params_purged);

	}

	/**
	 * Ajout de liaison post/categorie
	 * @param int   $id          post id
	 * @param array $valideArray list des id des categories selèctioné par l'utilisateur
	 */
	private function addCategorie(int $id,array $valideArray):bool
	{
		$id=(int)$id;


		$newValues=[];

		foreach ($valideArray as $value)
		{
			$v=(int)($value);
			$newValues[]="($id,$v)";
		}

		$implodeNewValues=implode(",",$newValues);

		$req=$this->pdo->prepare("INSERT INTO posts_categories (posts_id,categories_id) VALUES $implodeNewValues ");

		return $req->execute([]);

	}


	/**
	 * Insert post in DB width categorie Liaison
	 * @param  ServerRequestInterface $ServerRequest 
	 * @param  array  $categorieIdLIst list des id envoyés par l'utilisateur
	 * @param  array   $valideCategorieIdList list des id des categories present dans la DB 
	 * @return bool
	 */
	private function createPostWithCategorie(ServerRequestInterface $ServerRequest,array $categorieIdLIst,array $valideCategorieIdList):bool
	{

		$valideArray=Helper::purgeArray($categorieIdLIst,$valideCategorieIdList);

		if (!empty($valideArray))
		{
			$this->pdo->beginTransaction();

				$postCreationStatus=$this->createElement($ServerRequest->getParsedBody(),$this->validKeys);

				$lastPostId=(int)$this->pdo->lastInsertId();

				$categorieAddStatus=$this->addCategorie($lastPostId,$valideArray);

			$this->pdo->commit();

			return $postCreationStatus&$categorieAddStatus?true:false;

		}else 
		{
			$this->session->setSession("invalideForm",$this->message["invalideCategorie"]);

			return false;
		}

	}

	
	
}


?>