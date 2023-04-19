<?php 
namespace App\modules;

use GuzzleHttp\Psr7\ServerRequest;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Utils\globalActions\PostsPaginate;
use Utils\modele\Post;
use Utils\render\Render;
use Utils\router\Router;

class BlogModule extends PostsPaginate
{

    protected string $tableName="posts";

    protected string $orderBy="created_at" ;

    protected string $dbClass=Post::class ;

    protected string $baseUrl="/";


    /**
     * Nombre d'elements par page
     * @var integer
     */
    protected int $limit=7;

    public function __construct(
            private Router $router,
            private Render $render,
            public PDO $pdo
    )
    {

        parent::__construct($pdo,ServerRequest::fromGlobals());
        
        $this->router->map("GET","/",[$this,"home"],"blog_home");
    }

    public function home():string|ResponseInterface
    {
      
        $posts=$this->getPosts();

        $links=$this->getLinks();

        return $this->render->show("homeViews/home",parameter:[ "posts"=>$posts,"paginateLinks"=>$links]);
    }

}


?>