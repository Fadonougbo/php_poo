<?php 

namespace Utils\database;

use \PDO;

class DB 
{


	public static $pdo=null;

	/**
	 * PDO instance
	 * @param  string $dbname nom de la DB
	 * @return PDO       PDO instance
	 */
	public static function getPdoConnection(string $dbname):PDO
	{
		if (empty(self::$pdo))
		{
			self::$pdo=new PDO("pgsql:host=localhost;dbname=$dbname","root","root",
				[
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ
				]);
		}

		return self::$pdo;
	}
}

?>