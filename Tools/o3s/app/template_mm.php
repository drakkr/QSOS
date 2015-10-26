<?php
/*
**  Copyright (C) 2009-2012 Atos 
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
** template_mm.php: show the template of a given family with FreeMind Flash Viewer
**
*/
session_start();

include("config.php");
include("lang.php");

$family = $_REQUEST['family'];
$qsosspecificformat = $_REQUEST['qsosspecificformat'];
if (!isset($family)) die("No QSOS family to process");

$IdDB = mysqli_connect($db_host ,$db_user, $db_pwd, $db_db);

echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
echo "<LINK REL=StyleSheet HREF='skins/$skin/o3s.css' TYPE='text/css'/>\n";
echo "<title>Template Flash Viewer</title>";
echo "</head>\n";
echo "<body>\n";
echo "<center>\n";
echo "<img src='skins/$skin/o3s-$git.png'/>\n";
echo "<br/><br/>\n";

$query = "SELECT DISTINCT CONCAT(qsosappfamily,qsosspecificformat) FROM evaluations WHERE appname <> '' AND language = '$lang'";
$IdReq = mysqli_query($IdDB, $query);
$familiesFQDN = array();
while($row = mysqli_fetch_row($IdReq)) {
  array_push($familiesFQDN, $row[0]);
}
if (!in_array($family.$qsosspecificformat,$familiesFQDN)) 
  die ("$family $qsosspecificformat".$msg['s3_err_no_family']);

$query = "SELECT file FROM evaluations WHERE qsosappfamily = \"$family\" AND qsosspecificformat = '$qsosspecificformat' LIMIT 0,1";
$IdReq = mysqli_query($IdDB, $query);

if ($file = mysqli_fetch_row($IdReq)) {
  # LOAD XML FILE
  $XML = new DOMDocument();
  $XML->load($repo.$file[0]);

  # START XSLT
  $xslt = new XSLTProcessor();

  # IMPORT STYLESHEET
  $XSL = new DOMDocument();
  $XSL->load("../formats/xml/xslt/template-mm.xsl");
  $xslt->importStylesheet($XSL);

  #SAVE RESULT
  $name = $family.(($qsosspecificformat)?"-".$qsosspecificformat:"").".mm";
  $filename = "images/".$name;
  $file = fopen($filename, "w");
  fwrite($file, $xslt->transformToXML($XML));
  fclose($file);

  #DISPLAY RESULT WITH FLASHVIEWER
echo "<div style='font-weight: bold'>Template $name<br/><br/>\n";
echo "</center>\n";

echo '<script type="text/javascript" src="mindmap/flashobject.js"></script>
<div id="flashcontent"> Flash plugin or Javascript are turned off. Activate both  and reload to view the mindmap</div>
<script type="text/javascript">
// <![CDATA[
var fo = new FlashObject("mindmap/visorFreemind.swf", "visorFreeMind", "100%", "100%", 6, "");
fo.addParam("quality", "high");
fo.addParam("bgcolor", "#ffffff");
fo.addVariable("initLoadFile", "'.$filename.'");
fo.write("flashcontent");
// ]]>
</script>';

} else {
  print "Error: no $family ($qsosspecificformat) found in QSOS database!";
}
?> 
</body>
</html>
