<?php
/*
 * Authors :
 *	- Gabriel ZAAFRANI
 *	- Hery RANDRIAMANAMIHAGA
 *
 * Description : This class is for the object implementation
 *
 */

class Connexion {

	private $conf = "dataconf.ini";
	private $type; 
	private $server;
	private $database;
	private $login;
	private $password;
	private $port;
	private $dbh = null;


	public function __construct($type=null){
		$db_config = parse_ini_file($this->conf, true);
		$this->type     = $db_config["database"]["type"];
		$this->server   = $db_config["database"]["host"];
		$this->database = $db_config["database"]["name"];
		$this->login    = $db_config["database"]["user"];
		$this->password = $db_config["database"]["pass"];
		$this->port     = $db_config["database"]["port"];
		$connect = $this->type.':host='.$this->server.';dbname='.$this->database;
		$this->dbh = new PDO($connect,$this->login,$this->password);
	}

	public function select($query,$array){
		try{ 
			$statement = $this->dbh->prepare($query);
			$statement->execute($array);
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			return $e->getMessage();
		}
	} 


	public function __destruct(){
		unset($dbh);
	}



}
?>
