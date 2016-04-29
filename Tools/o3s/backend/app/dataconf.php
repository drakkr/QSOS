<?php

// Read ini file
$conf = "dataconf.ini";
if(!file_exists($conf))
	die(TXT_DBCONF_UNDEF . $conf); 

$db_config = parse_ini_file($conf, true);

// Build DSN from ini file
if (! array_key_exists("type", $db_config["database"]))
	die(TXT_DBTYPE_UNDEF);
$DSN = $db_config["database"]["type"] . ":";

if (array_key_exists("host", $db_config["database"]))
	$DSN .= "host=" . $db_config["database"]["host"] . ";";

if (array_key_exists("port", $db_config["database"]))
	$DSN .= "port=" . $db_config["database"]["port"] . ";";

if (! array_key_exists("name", $db_config["database"]))
	die(TXT_DBNAME_UNDEF);

$DSN .= "dbname=" . $db_config["database"]["name"] . ";";

// Ensure username and password are defined
if (! array_key_exists("user", $db_config["database"]))
	die(TXT_DBUSER_UNDEF);

if (! array_key_exists("pass", $db_config["database"]))
	die(TXT_DBPASS_UNDEF);

/*
 *  Connection to the DB
 */

try
{
      $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
      $bdd = new PDO($DSN, $db_config["database"]["user"], $db_config["database"]["pass"], $pdo_options);
}
catch (Exception $e)
{
      die(TXT_ERROR . $e->getMessage());
}
?>
