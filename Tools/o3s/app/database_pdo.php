<?php
/*
* Author : Gabriel ZAAFRANI
* Description : This class is for the object implementation
*
* Constructor : the constructor is for initialise the connexion , the parameter by default is equal to mysql. 
*/


ini_set('display_errors',1);
class Connexion {

 private $server = "";
 private $nameDb = "";
 private $login = "";
 private $password = "";
 private $port = "";
 private $dbh = null;


public function __construct($type="mysql"){
 if($type!="mysql"){
   $this->dbh = new PDO("pgsql:host=".$this->server.";port=".$this->port.";dbname=".$this->nameDb.";user=".$this->login.";password=".$this->password."");
 }else{
 $connect = 'mysql:host='.$this->server.';dbname='.$this->nameDb;
 $this->dbh = new PDO($connect,$this->login,$this->password);
 }
}
/* 
This function is for query select.
 SAMPLE 
 1 (:name is the key in the query): First parameter : select id from evaluation where name = :name
 2 Array is Key ->  value : $arr = array(":name" => "Nicolas");
 
 Return array : Array[indice]["ColumnName"];
*/
  
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
