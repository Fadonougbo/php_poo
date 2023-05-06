<?php 

namespace Utils\middlewares;

use Exception;
use Interfaces\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddleware implements MiddlewareInterface
{

	private string $key;

	private string $sessionKey;

	private int $limite;

	public function __construct(private SessionInterface $session)
	{
		$this->sessionKey="csrf";
		$this->key="_csrf";
		$this->limite=50;
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if( in_array($request->getMethod(),["POST"]) )
		{
			$params=$request->getParsedBody()??[];

			if(!array_key_exists($this->key,$params))
			{
				throw new Exception("Token Csrf absent ");
			}else 
			{
				$csrfList=$this->session->getSession($this->sessionKey);

				if(in_array($params[$this->key],$csrfList ))
				{
					$this->removeOldToken($params[$this->key]);
					return $handler->handle($request);
				}else 
				{
					throw new Exception("Token Csrf absent  ");
				}
			}
			
		}else 
		{
			return $handler->handle($request);
		}
	}

	/**
	 * Génère un token
	 * @return [type] [description]
	 */
	private function getToken()
	{
		$token=str_shuffle(uniqid(random_int(0,15)));

		$csrfList=[$token];
		$this->session->setSession($this->sessionKey,$csrfList);
		$this->limiteToken();
		return $token;
	}

	public function getCsrfInput():string
	{
		return <<<HTML
			<input type="hidden" name="{$this->key}" value="{$this->getToken()}">
		HTML;
	}

	private function removeOldToken($token):void
	{
		$csrfList=$this->session->getSession($this->sessionKey)??[];

		$newCsrfList=array_filter($csrfList,function($el) use($token)
		{
			return $el!==$token;
		});

		$this->session->setSession($this->sessionKey,$newCsrfList);
	}

	private function limiteToken()
	{
		$csrfList=$this->session->getSession($this->sessionKey)??[];

		if(count($csrfList)>$this->limite)
		{
			array_shift($csrfList);
		}

		$this->session->setSession($this->sessionKey,$csrfList);
	}
}

?>