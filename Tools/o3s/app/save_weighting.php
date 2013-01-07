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
** O3S
** save_weighting.php: generates .qw file to user to store current weightings
**
*/

include("config.php");

$output = new DOMDocument("1.0","UTF-8");
$document = $output->createElement('document');

$header = $output->createElement('header');
$header->appendChild($output->createElement('family', $_POST['family']));
$document->appendChild($header);

$weighting = $output->createElement('weighting');
while (list($name, $value) = each($_POST)) { 
  if ($name != 'family') {
    $weight = $output->createElement('weight', $value);
    $weight->setAttribute("id", $name);
    $weighting->appendChild($weight);
  }
}

$document->appendChild($weighting);
$output->appendChild($document);
$filename = $temp.uniqid().".qw";
$download = $_POST["family"].".qw";
$output->save($filename);

header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="'.$download.'"');
readfile($filename);
exit;
?>