<?php
/*
**  Copyright (C) 2013 Atos 
**
**  Author: Raphael Semeteys <raphael.semeteys@atos.net>
**
**  This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
**  the Free Software Foundation; either version 2 of the License, or
**  (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
**  but WITHOUT ANY WARRANTY; without even the implied warranty of
**  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**  GNU General Public License for more details.
**
**  You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
**
**
** O3S - Backend for remote clients
** writeremote.php: stores QSOS evaluations uploaded from remote clients
**
*/

require("conf.php");

$errors = null;
$successes = null;

//function alertError($error) {
//  global $errors;
//  $errors .= $error."\n";
//}
//
//function alertSuccess($success) {
//  global $successes;
//  $successes .= $success."\n";
//}

if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) { 
  $login = $_POST['login'];

  //Check if the user exists
  $sql = 'SELECT count(*) FROM users WHERE login= ? AND pass_md5= ?'; 
  $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $sth->execute(array($_POST['login'], md5($_POST['pass'])));
  $data = $sth->fetchAll();
  
  //If the user exists upload the document
  if ($data[0][0] == 1) { 
    include("upload.inc");
    upload($_FILES['myfile'], $login);
  } elseif ($data[0][0] == 0) { 
      alertError(TXT_CONNECT_ERROR_PWD); 
  } else { 
      alertError(TXT_CONNECT_ERROR_DB); 
  } 
} else {
  alertError(TXT_REGISTER_EMPTY); 
} 

if (isset($errors)) {
  echo $errors;
} else {
  echo $successes;
  //echo "File successfully uploaded";
}
?>
