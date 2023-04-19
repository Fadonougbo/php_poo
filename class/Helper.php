<?php
namespace Utils;

use GuzzleHttp\Psr7\Response;
use AltoRouter;
use Psr\Http\Message\ResponseInterface;
use Utils\router\Router;

class Helper
{

    /**
     * Verifie si les elements ou key de myArray sont dans referenceArray
     * 
     * @param  array        $myArray        [description]
     * @param  array        $referenceArray [description]
     * @param  bool|boolean $useKey         [description]
     * @return array tableau contenant les elements commun de myArray et referenceArray
     */
    public static function purgeArray(array $myArray,array $referenceArray,?bool $useKey=false):array 
    {
        $arr=$myArray;

        $filter=$useKey?ARRAY_FILTER_USE_KEY:ARRAY_FILTER_USE_BOTH;

        return array_filter($arr,function($el) use($referenceArray)
        {   
            return in_array($el,$referenceArray);

        },$filter);
    }

    /**
     * permet de genener de une chaine de caractère sous la form ex=:ex,name=:name 
     * @param  array  $arr [description]
     * @return string      [description]
     */
    public static function generateUpdateEchapString(array $arr):string
    {
        $new_arr=[];

        foreach ($arr as $value)
        {
            $new_arr[]="$value=:$value";
        }

        return implode(",",$new_arr);
    }

    /**
     * permet de genener de une chaine de caractère sous la form :ex,:name
     * @param  array  $arr [description]
     * @return [type]      [description]
     */
    public static function generateInsertEchapString(array $arr):string
    {
        $new_arr=[];

        foreach ($arr as $value)
        {
            $new_arr[]=":$value";
        }

        return implode(",",$new_arr);
    }

    public static function urlRedirect(string $uri)
    {

        if($uri[-1]==="/" && $uri!=="/")
        {
            $newUri=substr($uri,0,-1);
            $response=(new Response())
                      ->withStatus(301)
                      ->withHeader("location",$newUri);
            return $response;
        }
    }

    /**
     * Redirection en cas d'utilisation d'un mauvais slug
     * @param  array      $slugInfo [normalSlug,userSlug,id]
     * @param  AltoRouter $router   
     * @return ResponseInterface
     */
    public static function badSlugRedirect(array $slugInfo,Router $router):ResponseInterface
    {

        $normalSlug=$router->generate("blog_post",["id"=>$slugInfo["id"],"slug"=>$slugInfo["normalSlug"] ] ) ;

        return new Response(301,["location"=>$normalSlug]);

    }

    /**
     * Redirection en cas d'utilisation d'un mauvais id
     * @param  string $redirectUrl 
     * @return ResponseInterface     new Response()
     */
    public static function badIdRedirect(string $redirectUrl):ResponseInterface
    {

        return new Response(301,["location"=>"$redirectUrl"]);
    }
}


?>