<?php 

namespace App\modules;

use Utils\globalActions\Read;
use Utils\render\Render;
use Utils\router\Router;
use \PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\Helper;
use Utils\modele\Post;
use Utils\PaginateElements;

class BlogFilterByCategorieModule extends Read
{

	private $params;
	private $slug;
	private $id;


	 public function __construct(
            private Router $router,
            private Render $render,
            public PDO $pdo
    )
    {
        
        $this->router->map("GET","/blog/category/[*:slug]_[i:id]",[$this,"home"],"blog_filterByCategory");

        if (isset($this->router->match()["params"]))
		{
			$this->params=$this->router->match()["params"];
		}

		if (isset($this->params["slug"]))
		{
			$this->slug=$this->params["slug"];
		}

		if (isset($this->params["id"]))
		{
			$this->id=$this->params["id"];
		}	

        parent::__construct($pdo);
    }

    public function home(ServerRequestInterface $ServerRequest):string|ResponseInterface
    {

    	$category=parent::fetchCurrentElement("categories",$this->id);

    	if(!$category)
		{
			return Helper::badIdRedirect("/");
		}

		if ($category->slug!==$this->slug)
		{
			 return Helper::badSlugRedirect("blog_filterByCategory",[ "slug"=>$category->slug, "id"=>$this->id ], $this->router);
		}


        $info=[

            "limit"=>6,
            "table"=>"posts_categories",
            "redirectUrl"=>"/",
            "baseUrl"=>$this->router->generate("blog_filterByCategory",[ "slug"=>$category->slug, "id"=>$this->id ]),
            "orderBy"=>"created_at",
            "filterCountById"=>[$category->id,"categories_id"]

        ];

        $paginateElements=new PaginateElements($this->pdo,$ServerRequest,$info);

        $posts=$paginateElements->fectchCategoriePaginatePost($this->id,Post::class);


        $links=$paginateElements->getLinks();

        //Recupère les articles liés a  la categorie
        
        $articleCategorieInfo=parent::getCurrentArticleCategoriesInfo($posts);

        $allCategoriesList=parent::getAllCategoriesList();

        return  $this->render->show("homeViews/homeFilterByCategory",parameter:[ 
        		"category"=>$category,
                "posts"=>$posts,
                "paginateLinks"=>$links,
                "articleCategorieInfo"=>$articleCategorieInfo,
                "allCategoriesList"=>$allCategoriesList
            ]);
    }
}


?>