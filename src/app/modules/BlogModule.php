<?php 
namespace App\modules;

use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\globalActions\Read;
use Utils\modele\Post;
use Utils\PaginateElements;
use Utils\render\Render;
use Utils\router\Router;

class BlogModule extends Read
{

    public function __construct(
            private Router $router,
            private Render $render,
            public PDO $pdo
    )
    {
        
        $this->router->map("GET","/",[$this,"home"],"blog_home");

        parent::__construct($pdo);
    }

    public function home(ServerRequestInterface $ServerRequest):string|ResponseInterface
    {
        $info=[

            "limit"=>7,
            "table"=>"posts",
            "baseUrl"=>$this->router->generate("blog_home"),
            "orderBy"=>"created_at",

        ];

        $paginateElements=new PaginateElements($this->pdo,$ServerRequest,$info);

        $posts=$paginateElements->fectchPaginatePost(Post::class);

        $links=$paginateElements->getLinks();

        //Recupère les categorie lié au article
        $articleCategorieInfo=parent::getCurrentArticleCategoriesInfo($posts);

        return $this->render->show("homeViews/home",parameter:[ 
                "posts"=>$posts,
                "paginateLinks"=>$links,
                "articleCategorieInfo"=>$articleCategorieInfo
            ]);
    }

}


?>