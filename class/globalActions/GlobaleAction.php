<?php 

namespace Utils\globalActions;

use \PDO;

class GlobaleAction
{
	public function __construct(

		private PDO $pdo
	){

	}

    public function fetchCurrentElement(string $table,int $id)
    {
        if (is_string($id))
        {
           $id=(int)$id;
        }

        $query=$this->pdo->prepare("SELECT * FROM $table WHERE id=:id");
        $query->execute(["id"=>$id]);

        return $query->fetch();
    }

	/**
     * Recupére l'id des eléments present sur la page ou d'un element
     * @param  mixed  $posts [description]
     * @return [type]        [description]
     */
    public function getCurrentElementIdList($posts):string
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
    public function getCurrentArticleCategoriesInfo($posts):array
    {

        $idList=$this->getCurrentElementIdList($posts);

        if(empty($idList))
        {
           return [];
        }

        $sqlReq="SELECT categories.name,categories.slug as c_slug,posts.id AS p_id,categories.id As c_id  FROM posts_categories 
                LEFT JOIN posts ON posts.id=posts_categories.posts_id 
                LEFT JOIN categories ON categories.id=posts_categories.categories_id
                WHERE posts_categories.posts_id  IN($idList) ";
        
        $req=$this->pdo->prepare($sqlReq);

        $req->execute([]);

        $res=$req->fetchAll();

        
        $association=[];

        foreach($res as $el)
        {
            $association[$el->p_id][]=["name"=>$el->name,"slug"=>$el->c_slug,"id"=>$el->c_id];
        }
        return $association;
        
    }


    /**
     * Recupère toutes les categories
     * @return array
     */
    public function getAllCategoriesList():array
    {
        $sqlReq="SELECT * FROM categories";

        $req=$this->pdo->prepare($sqlReq);

        $req->execute([]);

        $categories=$req->fetchAll();

        return $categories;
    }


    public function slugExistVerification(string $table,string $slug,?int $id=null)
    {

        $query="SELECT COUNT(*) as total FROM $table WHERE slug=:slug ";
        $params=["slug"=>$slug];

        if(!empty($id))
        {
            $query.=" AND id NOT IN(:id) ";
            $params=array_merge($params,["id"=>(int)$id]);
        }

        $req=$this->pdo->prepare($query);

        $req->execute($params);

        $res=$req->fetchAll();

        $exist=$res[0]->total>=1?true:false;

        return $exist;
    }
}

?>