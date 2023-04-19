<?php
require "../vendor/autoload.php";

use App\App;
use App\modules\AdminModule;
use App\modules\BlogModule;
use App\modules\CreatePostModule;
use App\modules\DeletePostModule;
use App\modules\PostShowModule;
use App\modules\UpdatePostModule;
use App\modules\catgories_admin\CategoriesAdminModule;
use App\modules\catgories_admin\CreateCategorieModule;
use App\modules\catgories_admin\DeleteCategorieModule;
use App\modules\catgories_admin\UpdateCategorieModule;
use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;

try 
{

/*Mise en place du timestamp globale*/

date_default_timezone_set("Africa/Porto-Novo");


/*Mise en place du container*/
$builder=new ContainerBuilder();
$builder->addDefinitions(__DIR__.DIRECTORY_SEPARATOR."container_config.php");
$container=$builder->build();

/*Mise en place des modules*/
$app = new App($container,
[   
    /*Admin post crud module*/
    UpdatePostModule::class,
    CreatePostModule::class,
    DeletePostModule::class,

    /*Admin module*/
    AdminModule::class,

    /*Categorie crud module*/
    UpdateCategorieModule::class,
    CreateCategorieModule::class,
    DeleteCategorieModule::class,

    /*Categorie admin module*/
    CategoriesAdminModule::class,
    
    /*Show post module*/
    PostShowModule::class,

    /*Home module*/
    BlogModule::class,

]
);

/*Recuperation de la response*/
$response=$app->run(ServerRequest::fromGlobals());

/*envoie de la response*/
send($response);


}catch(Exception $e)
{
    echo $e->getMessage();
    echo $e->getFile();
    echo $e->getLine();
}

 ?>
