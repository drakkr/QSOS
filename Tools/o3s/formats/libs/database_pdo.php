<?php
ini_set('display_errors',1);
class Connexion {

 private $server = "NOEYYRTE";
 private $nameDb = "o3s";
 private $login = "o3s";
 private $password = "qsos";
 private $port = "";
 private $dbh = null;


public function __construct($type="mysql"){
 if($type!="mysql"){
   $this->dbh = new PDO("pgsql:host=NOEYYRTE;port=5432;dbname=o3s;user=eei;password=edfpassword");
 }else{
 $connect = 'mysql:host='.$this->server.';dbname='.$this->nameDb;
 $this->dbh = new PDO($connect,$this->login,$this->password);
 }
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

public function control($val){
 if(count($val)==0){
  return False;
 }
 return True;
}


public function __destruct(){
 unset($dbh);
}



}
?>
