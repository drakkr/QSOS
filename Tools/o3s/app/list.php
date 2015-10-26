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
** list.php: lists evaluations in a given family
**
*/
session_start();

include("config.php");
include("lang.php");

$family = $_REQUEST['family'];
$qsosspecificformat = $_REQUEST['qsosspecificformat'];

$backURL = "index.php?lang=$lang";

echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
echo "<LINK REL=StyleSheet HREF='skins/$skin/o3s.css' TYPE='text/css'/>\n";
?>
<script>

function checkboxes() {
  var ok = false;
  var inputs = document.getElementsByTagName("input");
  for(var i=0; i < inputs.length; i++) {
    if (inputs[i].type == "checkbox" && inputs[i].name == "id[]" && inputs[i].checked) {
      ok = true;
    }
  }
  return ok;
}

function clickAll() {
  var checked = document.getElementById("all").checked;
  var inputs = document.getElementsByTagName("input");
  for(var i=0; i < inputs.length; i++) {
    if (inputs[i].type == "checkbox" && inputs[i].name == "id[]") {
      inputs[i].checked = checked;
    }
  }
}

function submitForm() {
  if (checkboxes() == true) {
    myForm.action = "show.php";
    myForm.submit();
  } else {
    alert("<?php echo $msg['s3_err_js_no_file']; ?>");
  }
}

function showTemplate() {
    myForm.action = "template_mm.php";
    myForm.submit();
}

function exportODS() {
  if (checkboxes() == true) {
    myForm.action = "exportODS.php";
    myForm.submit();
  } else {
    alert("<?php echo $msg['s3_err_js_no_file']; ?>");
  }
}

function exportODP() {
  if (checkboxes() == true) {
    myForm.action = "exportODP.php";
    myForm.submit();
  } else {
    alert("<?php echo $msg['s3_err_js_no_file']; ?>");
  }
}

function exportODT() {
  if (checkboxes() == true) {
    myForm.action = "exportODT.php";
    myForm.submit();
  } else {
    alert("<?php echo $msg['s3_err_js_no_file']; ?>");
  }
}

function showGraph() {
  if (checkboxes() == true) {
    myForm.action = "radar.php";
    myForm.submit();
  } else {
    alert("<?php echo $msg['s3_err_js_no_file']; ?>");
  }
}

function showQuadrant() {
  if (checkboxes() == true) {
    myForm.action = "quadrant.php";
    myForm.submit();
  } else {
    alert("<?php echo $msg['s3_err_js_no_file']; ?>");
  }
}

function setWeights() {
  myForm.action = "set_weighting.php";
  myForm.submit();
}
</script>
<?php
echo "</head>\n";

echo "<body>\n";
echo "<center>\n";
echo "<img src='skins/$skin/o3s-$git.png'/>\n";
echo "<br/><br/>\n";

//Check if family and template version exist
$IdDB = mysqli_connect($db_host ,$db_user, $db_pwd, $db_db);
$query = "SELECT DISTINCT CONCAT(qsosappfamily,qsosspecificformat) FROM evaluations WHERE appname <> '' AND language = '$lang'";
$IdReq = mysqli_query($IdDB, $query);
$familiesFQDN = array();
while($row = mysqli_fetch_row($IdReq)) {
  array_push($familiesFQDN, $row[0]);
}
if (!in_array($family.$qsosspecificformat,$familiesFQDN)) 
  die ("$family $qsosspecificformat".$msg['s3_err_no_family']);
  
echo "<div style='font-weight: bold'>".$msg['s3_family'].$family."<br/><br/>"
  .$msg['s3_title']."<br/><br/>\n";
echo "<input type='button' value='".$msg['s3_button_back']."' 
  onclick='location.href=\"$backURL\"'/><br/><br/>\n";
  
echo "<form id='myForm' action='show.php'>\n";
echo "<input type='hidden' name='lang' value='$lang'/>\n";
echo "<input type='hidden' name='family' value='$family'/>\n";
echo "<input type='hidden' name='qsosspecificformat' value='$qsosspecificformat'/>\n";
echo "<table>\n";
echo "<tr class='title'>
  <td rowspan='2' align='center'>".$msg['s3_software']."</td>
  <td rowspan='2' align='center'>".$msg['s3_table_completed']."</td>
  <td rowspan='2' align='center'>".$msg['s3_table_commented']."</td>
  <td colspan='3' align='center'>".$msg['s3_table_view']."</td>
  <td rowspan='2' align='center'>".$msg['s3_table_compare']."</td>
</tr>\n";
echo "<tr class='title'>
  <td align='center'> ".$msg['s3_format_xml']." </td>
  <td align='center'> ".$msg['s3_format_html']." </td>
  <td align='center'> ".$msg['s3_format_freemind']." </td>
</tr>\n";

$query = "SELECT DISTINCT appname FROM evaluations WHERE qsosappfamily = \"$family\" AND qsosspecificformat = '$qsosspecificformat' ORDER BY appname";
$IdReq = mysqli_query($IdDB, $query);

while($appname = mysqli_fetch_row($IdReq)) {
  echo "<tr class='level0'><td colspan='7'>$appname[0]</td></tr>\n";
  $query2 = "SELECT id, e.release, qsosspecificformat, licensedesc,  criteria_scored/criteria_scorable, criteria_commented/comments, file FROM evaluations e WHERE appname = \"$appname[0]\" ORDER BY e.release";
  $IdReq2 = mysqli_query($IdDB, $query2);
  while($software = mysqli_fetch_row($IdReq2)) {
    echo "<tr class='level1' 
            onmouseover=\"this.setAttribute('class','highlight')\" 
            onmouseout=\"this.setAttribute('class','level1')\">\n";
    echo "<td align='center'>$software[1]</td>\n";
    echo "<td align='center'>".ceil($software[4]*100)."% </td>\n";
    echo "<td align='center'>".ceil($software[5]*100)."% </td>\n";
    echo "<td align='center'>
            <a href='$repo$software[6]'><img src='skins/$skin/xml.png' border='0' title='".$msg['s3_format_xml_tooltip']."'/></a>
            </td>\n";
    echo "<td align='center'>
            <a href='html.php?id=$software[0]'><img src='skins/$skin/html.png' border='0' title='".$msg['s3_format_html_tooltip']."'/></a>
            </td>\n";
    echo "<td align='center'>
            <a href='mm.php?lang=$lang&id=$software[0]'><img src='skins/$skin/freemind.png' border='0' title='".$msg['s3_format_freemind_tooltip']."'/></a>
            </td>\n";
    echo "<td align='center' class='html'>
            <!--span class='logo_html'/-->
            <input type='checkbox' class='logo_html' name='id[]' value='$software[0]'>
            </td></tr>\n";
  }
}
echo "<tr><td colspan=6></td><td style='text-align: center'>".$msg['s3_export_all']." <input type='checkbox' id='all' onclick='clickAll()'></td></tr>";
echo "</table><br/>";
echo $msg['s3_template']."</br>";
echo "<input type='button' value='".$msg['s3_set_weights']."' onclick='setWeights()'>";
echo "&nbsp;";
echo "<input type='button' value='".$msg['s3_show_mindmap']."' onclick='showTemplate()'>";
echo "<br/><br/>";
echo $msg['s3_compare']."</br>";
echo "<input type='button' value='".$msg['s3_button_next']."' onclick='submitForm()'>";
echo "&nbsp;";
echo "<input type='button' value='".$msg['s3_graph']."' onclick='showGraph()'>";
echo "&nbsp;";
echo "<input type='button' value='".$msg['s3_quadrant']."' onclick='showQuadrant()'>";
echo "<br/><br/>";
echo $msg['s3_export']."</br>";
echo "<input type='button' value='".$msg['s3_format_ods']."' onclick='exportODS()'>";
echo "&nbsp;";
echo "<input type='button' value='".$msg['s3_format_odp']."' onclick='exportODP()'>";
echo "&nbsp;";
echo "<input type='button' value='".$msg['s3_format_odt']."' onclick='exportODT()'>";
echo "</form></div>\n";

echo "</center>\n";
echo "</body>\n";
echo "</html>\n";
?>
