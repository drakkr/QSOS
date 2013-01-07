<?php
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
**
** O3S - Backend for remote clients
** loadremote.php: returns XML list of available templates or evaluations
**
*/

include("conf.php");

//Should we return the templates list or the evaluations list?
$selector = $_REQUEST["tpl"];
if (isset($selector) && $selector == "yes") $list_templates = true;


//Return a XML list of existing templates in the repositoty
function getListTemplates() {
  global $output;

  require('dataconf.php');
  $tpl_info = $bdd->query("SELECT file, qsosappfamily, qsosspecificformat, language, repo FROM templates ORDER BY qsosappfamily ASC, qsosspecificformat DESC");

  $templates = $output->createElement("templates");

  while ($info = $tpl_info->fetch()) {
    $newtreeitem = $output->createElement("item");
    $newtreeitem->setAttribute("id", $info[0]);
    $newtreeitem->setAttribute("name", $info[1]);
    $newtreeitem->setAttribute("version", $info[2]);
    $newtreeitem->setAttribute("language", $info[3]);
    $newtreeitem->setAttribute("repo", $info[4]);
    $templates->appendChild($newtreeitem);
  } 

  return $templates;
}

function buildTreeSheets() {
  global $output;

  require('dataconf.php');
  $tpl_req = $bdd->query("SELECT DISTINCT qsosappfamily, qsosspecificformat, language, repo FROM evaluations 
    ORDER BY qsosappfamily ASC, qsosspecificformat DESC");

  $children = $output->createElement("children");
  while ($tpl_info = $tpl_req->fetch()) {
    $tpl_newitem = $output->createElement("item");
    $tpl_newitem->setAttribute("id", $tpl_info[0]."-".$tpl_info[1]."-".$tpl_info[2]."-".$tpl_info[3]);
    $tpl_newitem->setAttribute("name", $tpl_info[0]);
    $tpl_newitem->setAttribute("version", $tpl_info[1]);
    $tpl_newitem->setAttribute("language", $tpl_info[2]);
    $tpl_newitem->setAttribute("repo", $tpl_info[3]);

    $eval_req = $bdd->query("SELECT file, appname, evaluations.release, language, repo FROM evaluations 
      WHERE qsosappfamily = '$tpl_info[0]' AND qsosspecificformat = '$tpl_info[1]' AND language = '$tpl_info[2]' AND repo = '$tpl_info[3]'
      ORDER BY appname ASC, evaluations.release DESC");
    
    $evaluations = $output->createElement("children");
    while ($eval_info = $eval_req->fetch()) {
      $eval_newitem = $output->createElement("item");
      $eval_newitem->setAttribute("id", $eval_info[0]);
      $eval_newitem->setAttribute("name", $eval_info[1]);
      $eval_newitem->setAttribute("version", $eval_info[2]);
      $eval_newitem->setAttribute("language", $eval_info[3]);
      $eval_newitem->setAttribute("repo", $eval_info[4]);
      $evaluations->appendChild($eval_newitem);
    } 
    $tpl_newitem->appendChild($evaluations);
    $children->appendChild($tpl_newitem);
  } 

  return $children;
}

$output = new DOMDocument();
$doc = $output->createElement("Document");

if ($list_templates) {
	$output->appendChild(getListTemplates());
} else {
	$output->appendChild(buildTreeSheets());
}

header('Content-type: text/xml');
echo $output->saveXML();
?>