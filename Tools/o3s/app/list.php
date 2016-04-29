<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
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

$backURL = "index.php?lang=".$lang;

echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
echo "<LINK REL=StyleSheet HREF='skins/".$skin."/o3s.css' TYPE='text/css'/>\n";
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

$familiesFQDN = array();
$query = "SELECT DISTINCT CONCAT(qsosappfamily,qsosspecificformat) as concatenation  FROM evaluations WHERE appname <> '' AND language =:lang";
$objectConnect = new Connexion("pgsql");
$array = array(
 ":lang" => $lang
);
$resultat = $objectConnect->select($query,$array);

if(count($resultat)==0){
 echo "the array is empty";
}else{
$found = False;
for($i=0;$i<count($resultat);$i++){
    if($family.$qsosspecificformat==$resultat[$i]["concatenation"]){
     $found = True;
    }
    $familiesFQDN[$i]=$resultat[$i]["concatenation"];
}

if($found==False){
  die("$family $qsosspecificformat".$msg['s3_err_no_family']);
}
  
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

/*
	the variable is $family (:family => $family)
	the variable is $qsosspecificformat (:qsosspecificformat => $qsosspecificformat)
*/
$query = "SELECT DISTINCT appname FROM evaluations WHERE qsosappfamily = :family  AND qsosspecificformat = :qsosspecificformat ORDER BY appname";
$array = array(
  ":family"=>$family,
  ":qsosspecificformat" => $qsosspecificformat
);
$result = $objectConnect->select($query,$array);

//$IdReq = mysql_query($query, $IdDB);
if(0==count($result)){
 /*
  Create a log file
 */
 die("your array is empty");
}

for($i=0;$i<count($result);$i++){
 echo "<tr class='level0'><td colspan='7'>".$result[0]["appname"]."</td></tr>\n";
/*
  $query2 = "SELECT id, e.release, qsosspecificformat, licensedesc,  criteria_scored/criteria_scorable, criteria_commented/comments, file FROM evaluations e WHERE appname = \"$appname[0]\" ORDER BY e.release";
  $IdReq2 = mysql_query($query2, $IdDB);
*/
 $query2 = "SELECT id, e.release, qsosspecificformat, licensedesc,  criteria_scored/criteria_scorable as div, criteria_commented/comments as div2, file,criteria_scored,criteria_scorable,criteria_commented,comments FROM evaluations e WHERE appname = :app ORDER BY e.release";

$arra = array(
   ":app" => $result[$i]["appname"]
);
$result = $objectConnect->select($query2,$arra);

/*
if($objectConnect->control($result)==False){
	/*
		Write Log

die("Error your query is empty");
}
*/

	
   for($i=0;$i<count($result);$i++){
     echo "<tr class='level1'
            onmouseover=\"this.setAttribute('class','highlight')\"
            onmouseout=\"this.setAttribute('class','level1')\">\n";
    echo "<td align='center'>".$result[$i]["release"]."</td>\n";
    echo "<td align='center'>".ceil(($result[$i]["criteria_scored"]/$result[$i]["criteria_scorable"]) *100)."% </td>\n";
    echo "<td align='center'>".ceil(($result[$i]["criteria_commented"]/$result[$i]["comments"])*100)."% </td>\n";
    echo "<td align='center'>
            <a href='".$repo.$result[$i]["file"]."'><img src='skins/".$skin."/xml.png' border='0' title='".$msg['s3_format_xml_tooltip']."'/></a>
            </td>\n";
    echo "<td align='center'>
            <a href='html.php?id=".$result[$i]["id"]."'><img src='skins/".$skin."/html.png' border='0' title='".$msg['s3_format_html_tooltip']."'/></a>
            </td>\n";
    echo "<td align='center'>
            <a href='mm.php?lang=".$lang."&id=".$result[$i]["id"]."'><img src='skins/".$skin."/freemind.png' border='0' title='".$msg['s3_format_freemind_tooltip']."'/></a>
            </td>\n";
    echo "<td align='center' class='html'>
            <!--span class='logo_html'/-->
            <input type='checkbox' class='logo_html' name='id[]' value='".$result[$i]["id"]."'>
            </td></tr>\n";
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
}
}
?>
