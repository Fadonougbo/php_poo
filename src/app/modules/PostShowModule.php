<?php 

namespace App\modules;

use Utils\render\Render;
use \PDO;
use Psr\Http\Message\ResponseInterface;
use Utils\globalActions\Read;
use Utils\Helper;
use Utils\router\Router;

class PostShowModule extends Read
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
		$router->map("GET","/show/[*:slug]_[i:id]",[$this,"showPost"],'blog_post');

		parent::__construct($pdo);

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

	public function showPost():string|ResponseInterface
	{	

		$post=parent::fetchCurrentElement("posts",$this->id);

		if (!$post)
		{
			return Helper::badIdRedirect("/");
		}

		if ($post->slug!==$this->slug)
		{
			 return Helper::badSlugRedirect('blog_post',[ "slug"=>$post->slug, "id"=>$this->id ], $this->router);
		}

		$articleCategorieInfo=parent::getCurrentArticleCategoriesInfo($post);


		return $this->render->show("postsView/post",parameter:["post"=>$post,"articleCategorieInfo"=>$articleCategorieInfo]);
	}

}

 ?>