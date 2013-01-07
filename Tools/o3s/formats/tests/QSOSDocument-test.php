<?php
/* Tests of the QSOSDocument class */

include('../libs/QSOSDocument.php');

$d = new QSOSDocument("test-evaluation.qsos");

function out($text) {
  echo $text."<br/><br/>";
}

out("checkXSD() : ".print_r($d->checkXSD('../xml/xsd/qsos.xsd')));

?>