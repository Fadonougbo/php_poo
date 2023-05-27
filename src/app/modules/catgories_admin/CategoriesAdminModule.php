<?php 

namespace App\modules\catgories_admin;

use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Utils\globalActions\Admin;
use Utils\modele\Categorie;
use Utils\render\Render;
use Utils\router\Router;
use \PDO;
use Psr\Http\Message\ServerRequestInterface;
use Utils\PaginateElements;

class CategoriesAdminModule extends Admin
{

    protected string $baseUrl="/admin/categories";

    protected string $urlName="admin_categories_home";

    public array $subModuleList=[
    	UpdateCategorieModule::class,
	    CreateCategorieModule::class,
	    DeleteCategorieModule::class
    ];

	public function __construct(
		protected Router $router,
        private Render $render,
        public PDO $pdo,
        protected RequestInterface $serverRequest,
		private SessionInterface $session
	)
	{
        parent::__construct($router,$pdo);
	}


	public function home(ServerRequestInterface $ServerRequest):string|ResponseInterface
	{

		$user=$this->session->getSession("userinfo");

        if(!$user)
        {
            return (new Response())->withHeader("Location","/login");
        }

		$info=[

            "limit"=>10,
            "table"=>"categories",
            "baseUrl"=>$this->baseUrl,
            "orderBy"=>"id"

        ];

        $paginateElements=new PaginateElements($this->pdo,$ServerRequest,$info);

		$categories=$paginateElements->fectchPaginatePost(Categorie::class);

        $links=$paginateElements->getLinks();

		return $this->render->show("adminViews/adminCategoriesView",parameter:[ 
			"categories"=>$categories,
			"paginateLinks"=>$links
		]);
	}

}

?>