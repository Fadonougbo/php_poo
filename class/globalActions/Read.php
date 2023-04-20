<?php 

namespace Utils\globalActions;

use \PDO;

class Read extends GlobaleAction
{

	public function __construct(
		
		  public PDO $pdo

	)
	{
		parent::__construct($pdo);
	}
}



?>