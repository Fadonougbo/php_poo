<?php
namespace Utils\globalActions;

use Interfaces\SessionInterface;
use PDO;
use Psr\Http\Message\ServerRequestInterface;
use Utils\router\Router;
use Utils\validation\FormValidator;

class Authentification extends GlobaleAction
{
    protected $validationStatus;
    protected bool $isConnect=false;
	protected array $messageList;
    private $user=null;

    public function __construct(
        private Router $router,
        public PDO $pdo,
        private SessionInterface $session
    )
    {
        
    }

    protected function userInfoVerification(ServerRequestInterface $ServerRequest)
    {
        if ($ServerRequest->getMethod()==="POST")
		{	

			$parsedBody=$ServerRequest->getParsedBody();

			if (!empty($parsedBody))
			{	
				//Verification des valeurs entrées
				$this->validationStatus=$this->formIsValid($parsedBody);

				if (!is_array($this->validationStatus))
				{
					
					$this->user=$this->connectUser($parsedBody);

					if (!$this->isConnect)
					{
						$this->session->setSession("no_connect",$this->messageList["no_connect"]);
					}

				}else 
				{
					$this->session->setSession("invalideForm",$this->messageList["invalideForm"]);
				}


			}
		}

		return $this->user;
    }

    protected function formIsValid(array $body)
	{
		return new FormValidator($body);
	}

    private function connectUser($parsedBody)
    {
        $name=$parsedBody["username"];
        $pass=$parsedBody["password"];
        $user=$this->getUser($name,$pass);

        if($user)
        {
            $this->session->setSession("userinfo",$user);
            $this->isConnect=true;
            return $user;
        }

        return false;
    }

    private function getUser(string $name,string $pass)
    {
        $query="SELECT * FROM users WHERE username=:username";

        $req=$this->pdo->prepare($query);

        $status=$req->execute([
            "username"=>$name
        ]);

        $user=$req->fetch();

        if($status && $user)
        {   
            $verifyPassword=password_verify($pass,$user->password);
            return $verifyPassword?$user:false;
        }

        return false;
    }
}


?>