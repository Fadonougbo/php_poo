<?php
require "../vendor/autoload.php";

use App\App;
use App\modules\AdminModule;
use App\modules\BlogFilterByCategorieModule;
use App\modules\BlogModule;
use App\modules\PostShowModule;
use App\modules\catgories_admin\CategoriesAdminModule;
use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;
use Utils\middlewares\CsrfMiddleware;
use Utils\middlewares\NotFoundMiddleware;
use Utils\middlewares\RunMiddleware;
use Utils\middlewares\SlashUrlRedirect;

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
    /*Admin module*/
    AdminModule::class,

    /*Categorie admin module*/
    CategoriesAdminModule::class,
    
    /*Show post module*/
    PostShowModule::class,

    /*Home module*/
    BlogModule::class,
    
    /*Articles list filter by categorie */
    BlogFilterByCategorieModule::class

]
);

/*Recuperation de la response*/

$req=ServerRequest::fromGlobals();

$app=$app->pipe(SlashUrlRedirect::class)
         ->pipe(CsrfMiddleware::class)
         ->pipe(RunMiddleware::class)
         ->pipe(NotFoundMiddleware::class)
         ;

/*envoie de la response*/
$response=$app->handle($req);

send($response);


}catch(Exception $e)
{
    echo $e->getMessage();
    echo $e->getFile();
    echo $e->getLine();
}

 ?>
