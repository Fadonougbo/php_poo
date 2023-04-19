<?php 

namespace Utils;

use PDO;
use Psr\Http\Message\ServerRequestInterface;
use Utils\database\DB;

class Paginate
{

	private PDO  $pdo;
	private int $totalElement;
	private int $totalPage;
	public int $paginationId;
	public string $currentPage;
	public array $queryParams;


	public function __construct(private ServerRequestInterface $serverInfo,private array $info)
	{
		 /**
		  * init pdo
		  * @var [type]
		  */
		 $this->pdo=DB::getPdoConnection("pooblog");

		 /**
		  * limit
		  * @var int
		  */
		 $limit=$this->info["limit"];

		 /**
		  * element total
		  * @var int
		  */
		 $this->totalElement=$this->countElement();

		 /**
		  * total page
		  * @var int
		  */
		 $this->totalPage=ceil($this->totalElement/$limit);

		 /**
		  * 
		  * @var integer
		  */
		 $this->paginationId=1;

		 /**
		  * get pagination query params
		  * @var array
		  */
		 $this->queryParams=$this->serverInfo->getQueryParams();

		 /**
		  * current page in url
		  * @var int
		  */
		 $this->currentPage=isset($this->queryParams["p"])?((int)$this->queryParams["p"]):1;

	}

	/**
	 * compte le nombre d'elements d'une table
	 * @param  string $table la table ciblé
	 * @return [type]        [description]
	 */
	public function countElement()
	{
		$query=$this->pdo->query("SELECT COUNT(*) AS total FROM {$this->info['table']} ");

		return $query->fetch()->total;
	}

	/**
	 * Verifie si /?p=1
	 * @return boolean [description]
	 */
	public function isOrigin():bool
	{

		 return ($this->serverInfo->getUri()->getPath()===$this->info["baseUrl"]) && ( isset($this->queryParams["p"]) ) && ( $this->currentPage==="1" ) ;
	}

	public function invalidePaginateParams():bool
	{
		return ($this->currentPage>$this->totalPage)||($this->currentPage<1);
	}

	public function getUrlList():array
	{

		 $urlList=[];

		 for ($i=1; $i <=$this->totalPage; $i++)
		 { 
		 	$urlList[]="/?p=$i";
		 }

		 return $urlList;
	}

	/**
	 * calcule le offset
	 * @return int offset
	 */
	public function getOffset():int
	{
		$currentPage=$this->currentPage;

		if($currentPage<=0||$currentPage>$this->totalPage)
		{
			$currentPage=1;
		}

		$limit=$this->info["limit"];


		return ($currentPage-1)*$limit;

	}

	/**
	 * code des boutons de navigation en HTML form 1
	 * @return string HTMLElement
	 */
	public function getHtmlA():string
	{

		$leftId=$this->currentPage-1;
		$rightId=$this->currentPage+1;
		$baseUrl=$this->info["baseUrl"];

		$leftLink=<<<HTML
				<button><a href="$baseUrl?p=$leftId">left</a></button> 
HTML;

		$rightLink=<<<HTML
				<button><a href="$baseUrl?p=$rightId">right</a></button> 
HTML;

		$links=$rightLink;

		if ($this->currentPage>1)
		{
			$links=$leftLink.$links;
		}

		if ($this->currentPage>=$this->totalPage)
		{
			$links=$leftLink;
		}

		return $links;

	}

	public function getHtmlB()
	{

		$baseUrl=$this->info["baseUrl"];

		$links="";

		for ($i=1; $i <=$this->totalPage; $i++)
		{ 
			$class=(int)$this->currentPage===$i?"activePage":"";

			$links.=<<<HTML
				<button class="$class"  ><a href="$baseUrl?p=$i">$i</a></button> 
HTML;
		}

		return $links;


	}

}

?>