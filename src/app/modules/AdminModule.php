<?php 

namespace App\modules;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Utils\globalActions\Admin;
use Utils\modele\Post;
use Utils\render\Render;
use Utils\router\Router;
use \PDO;
use Utils\RelationCategoriePost;

class AdminModule extends Admin
{

    protected string $tableName="posts";

    protected string $orderBy="created_at" ;

    protected string $dbClass=Post::class ;

    protected string $baseUrl="/admin";

    protected string $urlName="admin_home";

     /**
     * Nombre d'elements par page
     * @var integer
     */
    protected int $limit=7;

	public function __construct(
		private Router $router,
        private Render $render,
        public PDO $pdo,
        protected RequestInterface $serverRequest,
        private RelationCategoriePost $relationCategoriePost
	)
	{
        parent::__construct($router,$render,$pdo,$serverRequest);
	}


	public function home():string|ResponseInterface
	{

        $posts=$this->getPosts();

        $links=$this->getLinks();

        $categories_post=$this->relationCategoriePost->getCurrentCategoriesInfo($posts);


		return $this->render->show("adminViews/adminView",parameter:[ 
                                                                    "posts"=>$posts,
                                                                    "categories_post"=>$categories_post,
                                                                    "paginateLinks"=>$links
                                                                    ]);
	}


}

?>