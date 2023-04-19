<?php 

namespace Utils;
use \PDO;

class RelationCategoriePost
{

	public function __construct(private PDO $pdo)
	{

	}

	/**
     * Recupére l'id des elément present sur la page
     * @param  mixed  $posts [description]
     * @return [type]        [description]
     */
    public function getCurrentPostsIdList($posts):string
    {
    	$list=null;

    	if (is_array($posts))
    	{
    		$currentPostIdList=[];

	        foreach($posts as $el)
	        {
	            $currentPostIdList[]=$el->id;
	        }

	        $list=implode(", ",$currentPostIdList);

    	}else 
        {
            $list=$posts->id;
        }
         
         

        return $list;
    }

    /**
     * Associe les categories aux id des articles
     * @param  mixed  $posts [description]
     * @return [type]        [description]
     */
    public function getCurrentCategoriesInfo($posts):array
    {

        $idList=$this->getCurrentPostsIdList($posts);

        $sqlReq="SELECT categories.name,posts.id AS p_id,categories.id As c_id  FROM posts_categories 
                LEFT JOIN posts ON posts.id=posts_categories.posts_id 
                LEFT JOIN categories ON categories.id=posts_categories.categories_id
                WHERE posts_categories.posts_id  IN($idList) ";
        
        $req=$this->pdo->prepare($sqlReq);

        $req->execute([]);

        $res=$req->fetchAll();

        
        $association=[];

        foreach($res as $el)
        {
            $association[$el->p_id][]=["name"=>$el->name,"id"=>$el->c_id];
        }
        return $association;
        
    }


    public function getAllCategoriesList():array
    {
    	$sqlReq="SELECT * FROM categories";

    	$req=$this->pdo->prepare($sqlReq);

    	$req->execute([]);

    	$categories=$req->fetchAll();

    	return $categories;
    }

    public function getAllCategoriesId()
    {
        $sqlReq="SELECT id FROM categories";

        $req=$this->pdo->prepare($sqlReq);

        $req->execute([]);

        $categories=$req->fetchAll();

        $idList=array_map(function($el)
        {
            return $el->id;

        },$categories);

        return $idList;
    }

    public function getCurrentCategorieName(int $currentPostId,array $currentCategorieInfo):array
    {
    	$currentPostId=(int)$currentPostId;

    	$nameList=[];

    	if (isset($currentCategorieInfo[$currentPostId]))
    	{
    		$nameList=array_map(function($el)
	    	{

	    		return $el["name"];

	    	},$currentCategorieInfo[$currentPostId]);
    	}

    	

    	return $nameList;
    }

    public function getCurrentCategorieId(int $currentPostId,array $currentCategorieInfo)
    {
    	$currentPostId=(int)$currentPostId;

    	$idList=[];

    	if (isset($currentCategorieInfo[$currentPostId]))
    	{
    		$idList=array_map(function($el)
	    	{

	    		return $el["id"];

	    	},$currentCategorieInfo[$currentPostId]);

    	}

    	return $idList;

    	
    }
}

?>