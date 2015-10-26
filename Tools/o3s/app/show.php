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
** show.php: show QSOS evaluation(s)
**
*/
session_start();

$weights = $_SESSION;
$is_weighted = $_SESSION["nbWeights"];

//Search pattern
$s = $_REQUEST['s'];

include("config.php");
include("lang.php");

echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
echo "<LINK REL=StyleSheet HREF='skins/$skin/o3s.css' TYPE='text/css'/>\n";
?>
<script src="commons.js" language="JavaScript" type="text/javascript"></script>
<script src="search.js" language="JavaScript" type="text/javascript"></script>
<script>
var size = 12;

function showComments() {
  var cells = document.getElementsByTagName("td");
  for (var i = 0; i < cells.length; i++) {
    var c = cells[i];
    if (c.id == 'comment') {
      if (document.all) c.style.display = "block"; //IE4+ specific code
          else c.style.display = "table-row"; //Netscape and Mozilla
    }
  }
  document.getElementById("comment_selector").href = "javascript:hideComments();";
  document.getElementById("column").src = "<?php echo "skins/$skin/hide-comments.png"; ?>";
}

function hideComments() {
  var cells = document.getElementsByTagName("td");
  for (var i = 0; i < cells.length; i++) {
    var c = cells[i];
    if (c.id == 'comment') {
      c.style.display = "none";
    }
  }
  document.getElementById("comment_selector").href = "javascript:showComments();";
  document.getElementById("column").src = "<?php echo "skins/$skin/show-comments.png"; ?>";
}

function decreaseFontSize() {
  size--;
  document.getElementById("table").style.fontSize = size + "pt";
}

function increaseFontSize() {
  size++;
  document.getElementById("table").style.fontSize = size + "pt";
}

function submitForm(c) {
  document.getElementById("c").value = c;
  myForm.submit();
}
</script>
<?php
echo "</head>\n";

include("../formats/libs/QSOSDocument.php");

//Ids of QSOS XML files to be displayed
$ids = $_REQUEST['id'];
//Are graphs to be generated in SVG?
$svg = $_REQUEST['svg'];

function janitize($text) {
  return str_replace("\n",'',$text);
}

if (isset($s)) {
  echo "<body onload=\"highlightSearchTerms('$s');\">\n";
} else {
  echo "<body>\n";
}
echo "<center>\n";
echo "<img src='skins/$skin/o3s-$git.png'/>\n";
echo "<br/><br/>\n";
echo "<div style='font-weight: bold'>".$msg['s4_title']."<br/><br/>\n";

$IdDB = mysqli_connect($db_host ,$db_user, $db_pwd, $db_db);

$query = "SELECT id FROM evaluations WHERE appname <> '' AND language = '$lang'";
$IdReq = mysqli_query($IdDB, $query);
$allIds = array();
while($row = mysqli_fetch_row($IdReq)) {
  array_push($allIds, $row[0]);
}

$files = array();
foreach($ids as $id) {
  if (!(in_array($id,$allIds))) die($id.$msg['s4_err_no_id']);
  $query = "SELECT file FROM evaluations WHERE id = \"$id\"";
  $IdReq = mysqli_query($IdDB, $query);
  $result = mysqli_fetch_row($IdReq);
  array_push($files, $repo.$result[0]);
}

//echo "<form id='myForm' method='POST' action='radar.php'>\n";
//echo "<input type='hidden' name='svg' value='$svg'/>\n";
//echo "<input type='hidden' name='c' id='c' value=''/>\n";
//echo "<input type='hidden' name='s' value='$s'/>\n";

$num = count($files);
$myDoc = array();
$app = array();
$trees = array();

$i = 0;
foreach($files as $file) {
  $myDoc[$i] = new QSOSDocument($file);
  $app[$i] = $myDoc[$i]->getkey("appname")." ".$myDoc[$i]->getkey("release");
  if ($is_weighted) {
    $trees[$i] = $myDoc[$i]->getWeightedTree($weights);
  } else {
    $trees[$i] = $myDoc[$i]->getTree();
  }
  $i++;
}

$family = strtolower($myDoc[0]->getkey("qsosappfamily"));
$qsosspecificformat = $myDoc[0]->getkey("qsosspecificformat");

$f = "";
foreach($ids as $id) {
  $f .= "id[]=$id&";
}

echo "<table>\n";
echo "<tr width='100%' align='center'><td>\n";
echo "<a id='comment_selector' href='javascript:hideComments();'>";
echo "<img id='column' 
  src='skins/$skin/hide-comments.png' 
  border=0 
  onmouseover=\"return escape('Hide/Show comments')\"/>";
echo "</a>\n";
echo " <a href='javascript:decreaseFontSize();'>";
echo "<img src='skins/$skin/decrease-font.png' 
  border=0 
  onmouseover=\"return escape('Decrease font size')\"/>";
echo "</a>\n";
echo " <a href='javascript:increaseFontSize();'>";
echo "<img src='skins/$skin/increase-font.png' 
  border=0 
  onmouseover=\"return escape('Increase font size')\"/>";
echo "</a>\n";
if (!isset($s)) {
  echo " <a href='radar.php?lang=$lang&".$f."svg=$svg'>";
  echo "<img src='skins/$skin/graph.png' 
    border=0 onmouseover=\"return escape('Show graph')\"/>";
  echo "</a>\n";
}
echo "</td></tr>\n";
echo "<tr><td align='center'>\n";
if (isset($s)) {
  echo "<input type='button' 
    value='".$msg['s4_button_back_alt']
    ."' onclick='location.href=\"search.php?s=$s&lang=$lang\"'><br/><br/>";
} else {
  echo "<input type='button' 
    value='".$msg['s4_button_back']
    ."' onclick='location.href=\"list.php?lang=$lang&family=".$family."&qsosspecificformat=$qsosspecificformat&svg=$svg\"'><br/><br/>";
}

echo "</td></tr>\n";
echo "</table>\n";

echo "<table id='table' style='font-size: 12pt; table-layout: fixed'>\n";
echo "<tr class='title' style='width: 250px'>\n";
echo "<td rowspan='2'><div style='text-align: center'>$family</div></td>\n";
echo "<td style='width: 30px' rowspan='2'>";
if ($is_weighted) {
  echo "<img src='skins/$skin/graph.png' border='' style='cursor: pointer' onclick='submitForm(\"\")'/>";
}
echo "</td>\n";
for($i=0; $i<$num; $i++) {
  echo "<td colspan='2'><div style='width: 120px; text-align: center'>$app[$i]</div></td>\n";
  echo "<td id='comment' style='width: 300px'>".$msg['s4_comments']."</td>\n";
}
echo "</tr>\n";
echo "<tr class='title'>\n";
for($i=0; $i<$num; $i++) {
  echo "<td><div style='width: 60px; text-align: center'>".$msg['s4_score']."</div></td>\n";
  echo "<td><div style='width: 60px; text-align: center'>".$msg['s4_weight']."</div></td>\n";
  echo "<td id='comment' style='width: 300px'></td>\n";
}
echo "</tr>\n";

showtree($myDoc, $trees, 0, '', $weights);
echo "</table>\n";

function showtree($myDoc, $trees, $depth, $idP, $weights) {
  global $id, $f;
  global $files;
  global $svg;
  global $is_weighted;
  global $skin;
  global $lang;
  $new_depth = $depth + 1;
  $offset = $new_depth*10;
  $idF = 0;
  $tree = $trees[0];

  for($k=0; $k<count($tree); $k++) {
    $name = $tree[$k]->name;
    $title = $tree[$k]->title;
    $subtree = $tree[$k]->children;
    $subtrees = array();

    $idF++;
    if ($idP == '') {
      $idDOM = $idF;
    } else  {
      $idDOM = $idP."-".$idF;
    }

    echo "<tr id='$idDOM' 
      name='$name' 
      class='level$depth' 
      onmouseover=\"this.setAttribute('class','highlight')\" 
      onmouseout=\"this.setAttribute('class','level$depth')\">\n";
    if ($subtree) {
      echo "<td style='width: 250px; text-indent: $offset'>
        <span onclick=\"collapse(this);\" class='expanded'>$title</span>
        </td>\n";
      echo "<td style='width: 30px'>";
      if ($myDoc[0]->hassubelements($name) > 2) {
        if (!isset($s)) {
          echo "<a href='radar.php?lang=$lang&".$f."c=$name&svg=$svg'><img src='skins/$skin/graph.png' border='' style='cursor: pointer'/></a>\n";
        }
      }
    } else {
      echo "<td style='width: 250px; text-indent: $offset'>
        <span>$title</span>
        </td>\n";
      echo "<td style='width: 30px'></td>\n";;
    }

    for($i=0; $i<count($trees); $i++) {
      $desc = addslashes($myDoc[$i]->getgeneric($name, "desc".$trees[$i][$k]->score));
      if ($desc != "") {
        echo "<td class='score' 
          style='width: 60px; cursor:help' onmouseover=\"return escape('".janitize($desc)."')\">
          <div style='text-align: center'>"
            .$trees[$i][$k]->score
          ."</div></td>\n";
      } else {
        echo "<td class='score' 
          style='width: 60px; text-align: center'>
          <div style='text-align: center'>"
            .$trees[$i][$k]->score
          ."</div></td>\n";
      }
      if (!$is_weighted) {
        $weights[$name] = 1;
        $_SESSION[$name] = $weights[$name];
      }
      echo "<td>
        <div style='text-align: center'>"
          .$weights[$name]
        ."</div></td>\n";

      echo "<td id='comment'>
          <div style='width: 300px'>"
            .$myDoc[$i]->getgeneric($name, "comment")
          ."</div></td>\n";
    }
    echo "</tr>\n";

    if ($subtree) {
      for($i=0; $i<count($trees); $i++) {
        $subtrees[$i] = $trees[$i][$k]->children;
      }
      showtree($myDoc, $subtrees, $new_depth, $idDOM, $weights);
    }
  }
}

//echo "</form>";

echo "<br/>";
echo $msg['g_license_notice'];

echo "</center>\n";
echo "</body>\n";
echo "</html>\n";
?>
