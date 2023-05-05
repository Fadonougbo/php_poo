<?php 

namespace App\modules;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Utils\globalActions\Admin;
use Utils\modele\Post;
use Utils\render\Render;
use Utils\router\Router;
use \PDO;
use Psr\Http\Message\ServerRequestInterface;
use Utils\PaginateElements;
use Utils\RelationCategoriePost;

class AdminModule extends Admin
{

    protected string $urlName="admin_home";

    protected string $baseUrl="/admin";

    public array $subModuleList=[
             UpdatePostModule::class,
            CreatePostModule::class,
            DeletePostModule::class,
    ];


	public function __construct(
		protected Router $router,
        private Render $render,
        public PDO $pdo,
        protected RequestInterface $serverRequest,
        private RelationCategoriePost $relationCategoriePost
	)
	{
        parent::__construct($router,$pdo);
	}


	public function home(ServerRequestInterface $ServerRequest):string|ResponseInterface
	{

        $info=[

            "limit"=>7,
            "table"=>"posts",
            "baseUrl"=>$this->baseUrl,
            "orderBy"=>"created_at"

        ];

        $paginateElements=new PaginateElements($this->pdo,$ServerRequest,$info);

        $posts=$paginateElements->fectchPaginatePost(Post::class);


        $links=$paginateElements->getLinks();

        $categories_post=parent::getCurrentArticleCategoriesInfo($posts);


		return $this->render->show("adminViews/adminView",parameter:[ 
                                                                    "posts"=>$posts,
                                                                    "categories_post"=>$categories_post,
                                                                    "paginateLinks"=>$links
                                                                    ]);
	}


}

?>