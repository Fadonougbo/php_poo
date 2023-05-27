<?php 

namespace Utils\globalActions;


use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\Helper;
use \PDO;

class Delete 
{

	protected string $tableName;

	protected int $id;

	protected string $baseUrl;

	protected array $messageList=[];

	public function __construct(

		protected PDO $pdo,
		protected SessionInterface $session
	)
	{
		
	}

	protected function delete(ServerRequestInterface $ServerRequest)
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
			return Helper::badIdRedirect("{$this->baseUrl}");
		}

		if ($ServerRequest->getMethod()==="POST")
		{
				$delete=$this->deleteCurrentPost($post->id);

				if ($delete)
				{
					if(isset($post->pic))
					{
						$path=dirname(__DIR__,2).DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR.'pic'.DIRECTORY_SEPARATOR.$post->pic;
						unlink($path);
					}
					$this->session->setSession("success","{$this->messageList["success"]}");
					return (new Response())->withHeader("location","{$this->baseUrl}?p=$paginatePosition");
				}else 
				{
					return $this->session->setSession("no_success","{$this->messageList["no_success"]}");
				}

		}
	}

	/**
	 * Recupere des informations l'element courrant
	 * @return [type] [description]
	 */
	protected function fetchCurrentPost(int $id)
	{
		$query=$this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id=:id");
		$query->execute(["id"=>(int)$id]);

		return $query->fetch();
	}

	/**
	 * Supprime l'element courant
	 * @param  int    $id de l'élement
	 * @return bool
	 */
	protected function deleteCurrentPost(int $id):bool
	{

		$req=$this->pdo->prepare(" DELETE FROM {$this->tableName} WHERE id=:id ");

		return $req->execute(["id"=>(int)$id]);

	}
}


?>