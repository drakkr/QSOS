<?php
/*
**  Copyright (C) 2012 Atos 
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
** mm.php: MindMap export of en evaluation (Freemind .mm format)
**
*/
session_start();

//Weightings are stored in session
$weights = $_SESSION;
//QSOS evaluations to display
$id = $_REQUEST['id'];

include("config.php");

echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
echo "<LINK REL=StyleSheet HREF='skins/$skin/o3s.css' TYPE='text/css'/>\n";
echo "<title>Evaluation Flash Viewer</title>";
echo "</head>\n";
echo "<body>\n";
echo "<center>\n";
echo "<img src='skins/$skin/o3s-$git.png'/>\n";
echo "<br/><br/>\n";
echo "</center>\n";

include('../formats/libs/QSOSComparison.php');
$myComp = new QSOSComparison(array($id), $name);
$myComp->exportFreeMind($id, $use_flash_mm);

echo "/<body>\n";
echo "</html>\n";
?> 
