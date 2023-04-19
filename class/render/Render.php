<?php 
namespace Utils\render;

class Render
{

    public Array $globaleVar=[];

    public $test=["title"=>2];

    public $allP;

    /**
     * Retourn une vue sous form de tampon
     * @param string $view la vue
     * @param string $folder le dossier qui contient les vues
     * @param array|null $parameter les parametres des vues
     * @return bool|string
     */
    public function show(string $view,string $folder="views",?array $parameter=[]):string
    {
        $path=dirname(__DIR__,2).DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$view.".php";


        ob_start();
        
            $render=$this;
            extract($this->globaleVar);
            extract($parameter);
            require($path);

        $content=ob_get_clean();

        return $content;
    }

    /**
     * Add globale varible
     * @param array $varList list des variables à ajouter globalement
     */
    public function addGlobale(array $varList)
    {
        $this->globaleVar=$varList;
    }
}

?>