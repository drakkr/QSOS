<?php

/*
 *  Fill in this part
 */

/*
* Parse ini file 
*/
$file = "database_configuration.ini";
if(!file_exists($file))
{
 echo "The database_configuration.ini file is not present in your folder";
 error_log("*** The database_configuration.ini file is not present int your folder ***");
 exit();
}
$array_configuration = parse_ini_file($file,true);

// database renseignement
	$db_host = $array_configuration["database"]["host"];
        $db_user = $array_configuration["database"]["user"];
        $db_db = $array_configuration["database"]["dbname"];
        $db_pwd= $array_configuration["database"]["password"];
	$db_port = $array_configuration["database"]["port"];


/*
 *  Connection to the DB
 */

$type  = $array_configuration["database"]["name"];
$bdd="";
try
{
      $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
      if($type == "mysql"){
	 $bdd = new PDO("mysql:host=".$db_host.";dbname=".$db_db, $db_user, $db_pwd, $pdo_options);
        
      }elseif($type == "pgsql"){
	try{        
	$bdd = new PDO("pgsql:host=".$db_host.";port=".$db_port";dbname=".$db_db.";user=".$db_user.";password=".$db_pwd."");
       }catch(PDOException $e){
	  echo "the exception is ".$e->getMessage();
	}
      }
}
catch (Exception $e)
{
      die('Erreur : ' . $e->getMessage());
}
?>
