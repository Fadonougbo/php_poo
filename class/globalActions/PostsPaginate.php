<?php 

namespace Utils\globalActions;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Utils\Paginate;
use \PDO;

class PostsPaginate
{

	/**
	 * Nom de la table courante
	 */
	protected string $tableName;

	protected string $orderBy;

	protected string $dbClass;

	protected string $baseUrl;

	/**
	 * Nombre d'elements par page
	 * @var integer
	 */
	protected int $limit=5;


	protected Paginate $paginate;

	public function __construct(

		public PDO $pdo,
		protected RequestInterface $ServerRequest
	)
	{
		$this->paginate=new Paginate($ServerRequest,
			            [
			                "limit"=>$this->limit,
			                "table"=>$this->tableName,
			                "baseUrl"=>$this->baseUrl

			            ]);


        /**
         * redirige si ?p=1 || p>nombre_de_page|| p<nombre_de_page
         */
        if ($this->paginate->isOrigin() || $this->paginate->invalidePaginateParams())
        {
            return (new Response())->withHeader("location",$this->baseUrl);
        }
	}


	/**
	 * créée les bouton de navigation entre les pages
	 * @return string
	 */
	protected function getLinks()
	{
        
        /*$links=$paginate->getHtmlA();*/

        return $this->paginate->getHtmlB();
	}

	/**
     * get offset pour la database
     * @var int
     */
	protected function getOffset()
	{
		
        return $this->paginate->getOffset();
	}

	/**
	 * Recupère les elements à affiché
	 * @return array
	 */
	protected function getPosts()
	{
		$offset=$this->getOffset();

        return $this->fectchPaginatePost($this->limit,$offset);
	}

	/**
     * Recupère les elements dans la base de donnée
     * @param  int    $limit  limit 
     * @param  int    $offset 
     * @return array         
     */
    protected function fectchPaginatePost(int $limit , int $offset)
    {
        $query=$this->pdo->query("SELECT * FROM {$this->tableName} ORDER BY {$this->orderBy} DESC LIMIT $limit OFFSET $offset ");

        return $query->fetchAll(PDO::FETCH_CLASS,$this->dbClass);
    }


}


?>