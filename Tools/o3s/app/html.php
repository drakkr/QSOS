<?php
require_once("database_pdo.php");
/*
**  Copyright (C) 2007-2012 Atos 
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
** O3S
** html.php: HTML export of an evaluation
**
*/
$id = $_GET['id'];
if (!isset($id)) die("No QSOS file to process");

include("config.php");

$objetConnect = new Connexion("pgsql");
$query = "SELECT file FROM evaluations WHERE id = :id";
$array = array(
  ":id" => $id
);
$result = $objetConnect->select($query,$array);
if(0==count($result)){
 echo "Error: no evaluation #$id found in QSOS database!";	
}else{

  # LOAD XML FILE
  $XML = new DOMDocument();
  $XML->load($repo.$result[0]["file"]);

  # START XSLT
  $xslt = new XSLTProcessor();

  # IMPORT STYLESHEET
  $XSL = new DOMDocument();
  $XSL->load('../formats/xml/xslt/evaluation-xhtml.xsl');
  $xslt->importStylesheet($XSL);

  #PRINT
  print $xslt->transformToXML($XML);
}
?> 
