<?php 

namespace Utils;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Utils\Paginate;
use \PDO;

class PaginateElements
{

	private Paginate $paginate;

	public function __construct(

		private PDO $pdo,
		private RequestInterface $ServerRequest,
		private array $paginationInfo

	)
	{
		$this->paginate=new Paginate($ServerRequest,$paginationInfo);


        /**
         * redirige si ?p=1 || p>nombre_de_page|| p<nombre_de_page
         */
        if ($this->paginate->isOrigin() || $this->paginate->invalidePaginateParams())
        {
            return (new Response())->withHeader("location",$this->paginationInfo["baseUrl"]);
        }
	}


	/**
	 * créée les bouton de navigation entre les pages
	 * @return string
	 */
	public function getLinks()
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
	 * Recupère les elements dans la base de donnée
	 * @param  string|null $fetchClass fetch class name
	 * @return array
	 */
    public function fectchPaginatePost(?string $fetchClass=null):array
    {
    	$offset=$this->getOffset();
    	$limit=$this->paginationInfo['limit'];

        $query=$this->pdo->query("SELECT * FROM {$this->paginationInfo['table']} ORDER BY {$this->paginationInfo['orderBy']} DESC LIMIT $limit OFFSET $offset ");

        if(!empty($fetchClass))
        {
			return $query->fetchAll(PDO::FETCH_CLASS,$fetchClass);
        }else 
        {
        	return $query->fetchAll(PDO::FETCH_OBJ);
        }
    }

    /**
     * Recupère les articles liés a une categorie
     * @param int $categoryId 
     * @param  string|null $fetchClass [description]
     * @return [type]                  [description]
     */
    public function fectchCategoriePaginatePost(int $categoryId,?string $fetchClass=null):array
    {
    	$offset=$this->getOffset();
    	$limit=$this->paginationInfo['limit'];

    	$query= "SELECT posts.* FROM  {$this->paginationInfo['table']}
                LEFT JOIN posts ON posts.id=posts_categories.posts_id 
                LEFT JOIN categories ON categories.id=posts_categories.categories_id
                WHERE posts_categories.categories_id IN(:id) 
                LIMIT $limit OFFSET $offset";

        $query=$this->pdo->prepare($query);

        $query->execute(["id"=>$categoryId]);

        if(!empty($fetchClass))
        {
			return $query->fetchAll(PDO::FETCH_CLASS,$fetchClass);
        }else 
        {
        	return $query->fetchAll(PDO::FETCH_OBJ);
        }
    }


}


?>