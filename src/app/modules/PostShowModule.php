<?php 

namespace App\modules;

use Psr\Http\Message\ServerRequestInterface;
use Utils\render\Render;
use \PDO;
use Psr\Http\Message\ResponseInterface;
use Utils\Helper;
use Utils\router\Router;

class PostShowModule
{

	private $params;
	private $slug;
	private $id;

	public function __construct(

		private Router $router,
        private Render $render,
        private PDO $pdo
	)
	{
		$router->map("GET","/show/[*:slug]_[i:id]",[$this,"showPost"],'blog_post');

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
		
	}

	public function showPost(ServerRequestInterface $serverInfo):string|ResponseInterface
	{	

		$post=$this->fetchCurrentPost();

		if (!$post)
		{
			return Helper::badIdRedirect("/");
		}

		if ($post->slug!==$this->slug)
		{
			 return Helper::badSlugRedirect( [ "normalSlug"=>$post->slug, "id"=>$this->id ] , $this->router);
		}

		return $this->render->show("postsView/post",parameter:["post"=>$post]);
	}


	public function fetchCurrentPost()
	{
		$query=$this->pdo->prepare("SELECT * FROM posts WHERE id=:id");
		$query->execute(["id"=>$this->id]);

		return $query->fetch();
	}
}

 ?>