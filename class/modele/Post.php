<?php 

namespace Utils\modele;

class Post
{	
	public $id;
	public $name;
	public $slug;
	public $content;
	public $created_at;
	public $updated_at;
	public $pic;

	public function subStringContent(int $length):string
	{
       
       $content=$this->content;

       if (mb_strlen($content)>500)
       {
       	$pos=mb_strpos($content,".",$length);

		return mb_substr(nl2br($content),0,$pos+1);
       }

       return $content ;
		
	}
}


?>