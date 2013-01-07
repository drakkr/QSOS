<?php

/*
 *  Fill in this part
 */
$db_host = "localhost";
$db_user = "root";
$db_pwd = "osiris";
$db_db = "o3s";


/*
 *  Connection to the DB
 */
try
{
      $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
      $bdd = new PDO("mysql:host=$db_host;dbname=$db_db", $db_user, $db_pwd, $pdo_options);
}
catch (Exception $e)
{
      die('Erreur : ' . $e->getMessage());
}
?>
