<?php 

namespace App\modules\catgories_admin;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Utils\globalActions\Admin;
use Utils\modele\Categorie;
use Utils\render\Render;
use Utils\router\Router;
use \PDO;

class CategoriesAdminModule extends Admin
{
	protected string $tableName="categories";

    protected string $orderBy="id" ;

    protected string $dbClass=Categorie::class ;

    protected string $baseUrl="/admin/categories";

    protected string $urlName="admin_categoies_home";

     /**
     * Nombre d'elements par page
     * @var integer
     */
    protected int $limit=10;

	public function __construct(
		private Router $router,
        private Render $render,
        public PDO $pdo,
        protected RequestInterface $serverRequest
	)
	{
        parent::__construct($router,$render,$pdo,$serverRequest);
	}


	public function home():string|ResponseInterface
	{
		$posts=$this->getPosts();

        $links=$this->getLinks();

		return $this->render->show("adminViews/adminCategoriesView",parameter:[ "posts"=>$posts,"paginateLinks"=>$links]);
	}

}

?>