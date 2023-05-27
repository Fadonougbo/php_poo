<?php 
namespace App\modules\auth;

use GuzzleHttp\Psr7\Response;
use Interfaces\SessionInterface;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Utils\globalActions\Authentification;
use Utils\render\Render;
use Utils\router\Router;
use Utils\validation\FormValidator;

class AuthModule extends Authentification
{

    protected $validationStatus=null;
    protected array $messageList=[
		"success"=>"L'article à bien été modifié",
		"no_connect"=>"Username ou password Incorrecte",
		"invalideForm"=>"Veillez corriger vos erreurs",
	];

    public array $subModuleList=[
        LogoutModule::class
    ];

    public function __construct(
            private Router $router,
            private Render $render,
            public PDO $pdo,
            private SessionInterface $session
    )
    {
        
        $this->router->map("GET|POST","/login",[$this,"home"],"blog_login");

        parent::__construct($router,$pdo,$session);
    }

    public function home(ServerRequestInterface $ServerRequest):string|ResponseInterface
    {

        $user=parent::userInfoVerification($ServerRequest);

        if($user)
        {
            return (new Response())->withHeader('Location',"/admin");
        }

        return $this->render->show("auth/login",parameter:[ 
            "validationStatus"=>$this->validationStatus??false
            ]);
    }

    /**
	 * Validation des données
	 * @param  array  $body $_POST params
	 * @return array|bool     
	 */
	public function formIsValid(array $body):array|bool
	{

		return (new FormValidator($body))->required("username","password")
										 ->validate() ;
	}

}


?>