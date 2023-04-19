<?php 

namespace  Utils\session;
use Interfaces\SessionInterface;

class Session implements SessionInterface
{
    

	public function startSession(): void 
    {
        if (session_status()===PHP_SESSION_NONE)
        {
            session_start();
        }
	}
	
	/**
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getSession(string $key)
    {
        $this->startSession();

        if(array_key_exists($key,$_SESSION))
        {
            return $_SESSION[$key];
        }
	}

	/**
	 * Summary of getSessionFlash
	 * @param string $key
	 * @return mixed
	 */
	public function getSessionFlash(string $key) 
	{
		$content=$this->getSession($key);

		$this->deleteSession($key);

		return $content;
		
	}
	
	/**
	 *
	 * @param string $key
	 * @param  $value
	 * @return bool
	 */
	public function setSession(string $key,$value): bool
    {
        $this->startSession();

        $_SESSION[$key]=$value;
        return true;
	}
	
	/**
	 *
	 * @param string $key
	 * @return bool
	 */
	public function deleteSession(string $key): bool
    {
        $this->startSession();

        unset($_SESSION[$key]);

        return true;
	}

}

?>