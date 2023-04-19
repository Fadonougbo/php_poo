<?php 

namespace Utils\globalActions;


use Interfaces\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\Helper;
use Utils\router\Router;
use Utils\validation\FormValidator;
use \PDO;

class Update 
{

	protected string $tableName;

	protected PDO $pdo;

	protected string $baseUrl;

	protected string $urlName;

	protected $validationStatus;

	protected array $valideArrayKeys;

	protected bool $isUpdated=false;

	protected array $messageList;

	public function __construct(private Router $router,public SessionInterface $session)
	{
		$this->router->map("GET|POST",$this->baseUrl,[$this,"index"],$this->urlName);
	}

	protected function update(ServerRequestInterface $ServerRequest,$post,?array $valideCategorieIdList=null):bool
	{

		if ($ServerRequest->getMethod()==="POST")
		{	

			$parsedBody=$ServerRequest->getParsedBody();

			if (!empty($parsedBody))
			{	
				//Verification des valeurs entrées
				$this->validationStatus=$this->formIsValid($parsedBody);

				if (!is_array($this->validationStatus))
				{
					
					$this->runUpdate($post->id,$ServerRequest,$parsedBody,$valideCategorieIdList);

					if ($this->isUpdated)
					{
						$this->session->setSession("success",$this->messageList["success"]);
					}else 
					{
						$this->session->setSession("no_update",$this->messageList["no_update"]);
					}

				}else 
				{
					$this->session->setSession("invalideForm",$this->messageList["invalideForm"]);
				}


			}
		}

		return $this->isUpdated;
	}


	public function formIsValid(array $body)
	{
		return new FormValidator($body);
	}

	private function runUpdate(int $id,ServerRequestInterface $ServerRequest,$parsedBody,$valideCategorieIdList)
	{

		//Cas de modification d'une categorie
		if(empty($valideCategorieIdList) )
		{
			$this->isUpdated=$this->updateElement($id,$ServerRequest->getParsedBody(),$this->valideArrayKeys);

		}else if($valideCategorieIdList && !isset($parsedBody["categories_lists"]))
		{
			//Cas de modification d'un article sans selection de categorie
			$this->pdo->beginTransaction();

				$this->deleteOldCategorieLiaison($id);
				$this->isUpdated=$this->updateElement($id,$ServerRequest->getParsedBody(),$this->valideArrayKeys);

			$this->pdo->commit();

		}else if($valideCategorieIdList && isset($parsedBody["categories_lists"]))
		{	
			//Cas de modification d'un article avec presence de categorie

			/**
			 * Verifie si l'utilisateur a fait un selection dans les categorie disponible
			 * @var [type]
			 */
			$valideArray=Helper::purgeArray($parsedBody["categories_lists"],$valideCategorieIdList);

			if(!empty($valideArray))
			{
				$this->pdo->beginTransaction();

				$updateElement=$this->updateElement($id,$ServerRequest->getParsedBody(),$this->valideArrayKeys);
				$updateCategorieLiaisons=$this->updateCategorie($id,$parsedBody["categories_lists"],$valideCategorieIdList);

				$this->pdo->commit();

				$this->isUpdated=$updateCategorieLiaisons&&$updateElement?true:false;

			}else 
			{
				$this->session->deleteSession("no_update");
				$this->session->setSession("invalideForm",$this->messageList["invalideCategorieSelection"]);
			}

		}

	}

	/**
	 * Recupère des informations l'element courrant
	 * @return [type] [description]
	 */
	public function fetchCurrentPost(int|string $id)
	{
		if (is_string($id))
		{
		   $id=(int)$id;
		}
		$query=$this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id=:id");

		$query->execute(["id"=>(int)$id]);

		return $query->fetch();
	}



	/**
	 * update de l'element
	 * @param  int    $id             [description]
	 * @param  array  $parsedBody     [description]
	 * @param  array  $validArrayKeys [description]
	 * @return bool                [description]
	 */
	private function updateElement(int $id,array $parsedBody,array $validArrayKeys):bool
	{
		$params_purged=Helper::purgeArray($parsedBody,$validArrayKeys,true);

		$sqlEchapString=Helper::generateUpdateEchapString(array_keys($params_purged));

		$params_purged["id"]=(int)$id;

		$req=$this->pdo->prepare(" UPDATE {$this->tableName} SET $sqlEchapString WHERE id=:id ");

		return $req->execute($params_purged);

	}

	/**
	 * Supprime les anciennes liaison de l'article avec les categories
	 * @param  int    $id article id
	 * @return [type]     [description]
	 */
	private function deleteOldCategorieLiaison(int $id):bool
	{
		$sqlReq="DELETE FROM posts_categories WHERE posts_id=:id";

		$req=$this->pdo->prepare($sqlReq);

		return $req->execute(["id"=>$id]);

	}

	/**
	 * Ajout des nouvelles liaisons entre categorie et article
	 * @param  int    $id             id de l'article
	 * @param  array  $parsedBodyKey  les valeur(id des categorie) anvoyé par l'utilisateur au niveau de select
	 * @param  array  $validArrayKeys la list de tous les id des categories
	 * @return bool                [description]
	 */
	private function updateCategorie(int $id,array $validArrayKeys ):bool
	{
		$id=(int)$id;

			$deleteOldCategorieLiaison=$this->deleteOldCategorieLiaison($id);

			if ($deleteOldCategorieLiaison)
			{
				$newValues=[];

				foreach ($validArrayKeys as $value)
				{
					$v=(int)($value);
					$newValues[]="($id,$v)";
				}

				$implodeNewValues=implode(",",$newValues);

				$req=$this->pdo->prepare("INSERT INTO posts_categories (posts_id,categories_id) VALUES $implodeNewValues ");

				return $req->execute([]);
			}


		return false;
	}


}


?>