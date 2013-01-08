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
**
**  O3S Formats classes
**  QSOSComparison.php: PHP classes to access and manipulate QSOS comparison
**
*/

//Class representing a QSOS comparison
class QSOSComparison {
  //var $title; //Title of the comparison
  var $subtitle; //Subtitle of the comparison
  var $ids; //Id of QSOS evaluations
  var $docs; //QSOS Documents for evaluations
  var $names; //Names of evaluated products
  var $files; //Files of QSOS evaluations
  var $f; //Current params string for URLs
  var $criteria; //Current displayed criteria

  var $weights; //Weights of QSOS criteria
  var $lang;
  var $msg;
  var $temp;
  var $template;

  //********************************************
  // CONSTRUCTOR
  //********************************************

  function __construct($ids, $criteria) {
    $this->ids = $ids;
    //$this->title = $title;
    //$this->subtitle = "";
    $this->criteria = $criteria; //Used for Radar navigation

    include("../../app/config.php");
    include("../../app/lang.php");
    $this->lang = $lang;
    $this->msg = $msg;
    $this->temp = $temp;
    $this->template = $template;

    $IdDB = mysql_connect($db_host ,$db_user, $db_pwd);
    mysql_select_db($db_db);

    $query = "SELECT id FROM evaluations WHERE appname <> '' AND language = '$lang'";
    $IdReq = mysql_query($query, $IdDB);
    $allIds = array();
    while($row = mysql_fetch_row($IdReq)) {
      array_push($allIds, $row[0]);
    }

    //Initialization of QSOS files
    $this->files = array();
    foreach($this->ids as $id) {
      if (!(in_array($id,$allIds))) die("<error>Bad Id: $id</error>");
      $query = "SELECT file FROM evaluations WHERE id = \"$id\"";
      $IdReq = mysql_query($query, $IdDB);
      $result = mysql_fetch_row($IdReq);
      array_push($this->files, $repo.$result[0]);
    }

    //Initialization of QSOS Documents objects
    include('QSOSDocument.php');
    $this->docs = array();
    $this->names = array();
    for($i=0; $i<count($this->files); $i++) {
      $this->docs[$i] = new QSOSDocument($this->files[$i]);
      $this->names[$i] = $this->docs[$i]->getkey("appname")." ".$this->docs[$i]->getkey("release");
    }

    $f = "";
    foreach($ids as $id) {
      $f .= "id[]=$id&";
    }
    $this->f = $f;

  }

  function setCriteria($criteria) {
    $this->criteria = $criteria;
  }

  //********************************************
  // XSL methods
  //********************************************

  function applyXSLT($input_file, $stylesheet, $save, $output_file = null) {
    # LOAD XML FILE
    $XML = new DOMDocument();
    $XML->load($input_file);

    # START XSLT
    $xslt = new XSLTProcessor();

    # IMPORT STYLESHEET
    $XSL = new DOMDocument();
    $XSL->load($stylesheet);
    $xslt->importStylesheet($XSL);

    $result = $xslt->transformToXML($XML);

    if ($save) {
      $file_content = fopen($output_file, 'w');
      fwrite($file_content, $result);
      fclose($file_content);      
    } else {
      print $result;
    }
  }

  function showXSLT($input_file, $stylesheet) {
    applyXSLT($input_file, $stylesheet, false);
  }

  function saveXSLT($input_file, $stylesheet, $output_file) {
    applyXSLT($input_file, $stylesheet, true, $output_file);
  }

  function exportSection($doc, $name, $img) {
    //Get the section by name
    $section = $doc->getsection($name);
    $section_xml = $section->ownerDocument->saveXML($section);

    //Transform section in a DOMDocument
    $xml = new DOMDocument();
    $xml->loadXML($section_xml);

    # START XSLT
    $xslt = new XSLTProcessor();

    # IMPORT STYLESHEET
    $XSL = new DOMDocument();
    $XSL->load("../formats/xml/xslt/evaluation-section-mm.xsl");
    $xslt->importStylesheet($XSL);

    $mm = $xslt->transformToXML($xml);

    $file_content = fopen("$img.mm", 'w');
    fwrite($file_content, $mm);
    fclose($file_content);   

    //MM (FreeMind) file is transformed to PNG files
    exec("java -cp freemind/lib/freemind.jar freemind.view.mindmapview.IndependantMapViewCreator '$img.mm' '$img.png'");
    //echo "<img src='$img.png'/>";
  }

  function exportTemplateSection($doc, $name, $img) {
    //Get the section by name
    $section = $doc->getsection($name);
    $section_xml = $section->ownerDocument->saveXML($section);

    //Transform section in a DOMDocument
    $xml = new DOMDocument();
    $xml->loadXML($section_xml);

    # START XSLT
    $xslt = new XSLTProcessor();

    # IMPORT STYLESHEET
    $XSL = new DOMDocument();
    $XSL->load("../formats/xml/xslt/template-section-mm.xsl");
    $xslt->importStylesheet($XSL);

    $mm = $xslt->transformToXML($xml);

    $file_content = fopen("$img.mm", 'w');
    fwrite($file_content, $mm);
    fclose($file_content);   

    //MM (FreeMind) file is transformed to PNG files
    exec("java -cp freemind/lib/freemind.jar freemind.view.mindmapview.IndependantMapViewCreator '$img.mm' '$img.png'");
    //echo "<img src='$img.png'/>";
  }

  function exportFreeMind($id, $flash=false) {
    include("../../app/config.php");

    $IdDB = mysql_connect($db_host ,$db_user, $db_pwd);
    mysql_select_db($db_db);

    $query = "SELECT file FROM evaluations WHERE id = \"$id\"";
    $IdReq = mysql_query($query, $IdDB);

    if ($files = mysql_fetch_row($IdReq)) {
      $file = $files[0];
      $basename = basename($file, '.qsos');
      //Transform section in a DOMDocument
      $xml = new DOMDocument();
      $xml->load($repo.$file);

      # START XSLT
      $xslt = new XSLTProcessor();

      # IMPORT STYLESHEET
      $XSL = new DOMDocument();
      $XSL->load("../formats/xml/xslt/evaluation-mm.xsl");
      $xslt->importStylesheet($XSL);

      $mm = $xslt->transformToXML($xml);

      $file_content = fopen("images/$basename.mm", 'w');
      fwrite($file_content, $mm);
      fclose($file_content);   

      if (!$flash) {
	//MM (FreeMind) file is transformed to PNG files
	exec("java -cp freemind/lib/freemind.jar freemind.view.mindmapview.IndependantMapViewCreator 'images/$basename.mm' 'images/$basename.png'");
	echo "<img src='images/$file.png'/>";
      } else {
	echo '<script type="text/javascript" src="mindmap/flashobject.js"></script>
<p style="text-align:center; font-weight:bold"><a href="images/'.$basename.'.mm">Evaluation '.$basename.'.mm</a></p>
<div id="flashcontent"> Flash plugin or Javascript are turned off. Activate both  and reload to view the mindmap</div>
<script type="text/javascript">
// <![CDATA[
var fo = new FlashObject("mindmap/visorFreemind.swf", "visorFreeMind", "100%", "100%", 6, "");
fo.addParam("quality", "high");
fo.addParam("bgcolor", "#ffffff");
fo.addVariable("initLoadFile", "images/'.$basename.'.mm");
fo.write("flashcontent");
// ]]>
</script>';

      }
    } else {
      print "Error: no $file found in QSOS database!";
    }
  }

  //********************************************
  // RADAR methods
  //********************************************

  function Radar($save, $file='') {
    global $doc, $SCALE, $FONT_SIZE, $dx, $dy, $g, $myDoc, $msg, $lang, $f, $name, $save;

    $FONT_SIZE = 12; //$SCALE/10;
    $g;
    $doc = new DOMDocument('1.0');
    $myDoc = $this->docs;
    $lang = $this->lang;
    $f = $this->f;
    $num = count($this->ids);
    $name = $this->criteria;
    $msg = $this->msg;

    if (!$save) header("Content-type: image/svg+xml");

    //draw $n equidistant axis
    if (!function_exists('drawAxis')) {
    function drawAxis($n) {
      global $SCALE;

      drawCircle(0.5*$SCALE);
      drawMark(0.5*$SCALE-25, 15, "0.5");
      drawCircle($SCALE);
      drawMark($SCALE-15, 15, "1");
      drawCircle(1.5*$SCALE);
      drawMark(1.5*$SCALE-25, 15, "1.5");
      drawCircle(2*$SCALE);
      drawMark(2*$SCALE-15, 15, "2");
      
      //N: should be commented
      for ($i=1; $i < $n+1; $i++) {
	drawSingleAxis(2*$i*pi()/$n);
      }
    }}
    
    //draw a single axis at $angle (in radians) from angle 0	
    if (!function_exists('drawSingleAxis')) {
    function drawSingleAxis($angle) {
      global $SCALE, $dx, $dy;

      $x2 = 2*$SCALE*cos($angle) + $dx;
      $y2 = 2*$SCALE*sin($angle) + $dy;
      drawLine($dx, $dy, $x2, $y2);
    }}
    
    //draw a circle of $r radius
    if (!function_exists('drawCircle')) {
    function drawCircle($r) {
      global $dx, $dy, $doc, $g;

      $circle = $doc->createElement("circle");
      $circle->setAttribute("cx", $dx);
      $circle->setAttribute("cy", $dy);
      $circle->setAttribute("r", $r);
      $circle->setAttribute("fill", "none");
      $circle->setAttribute("stroke", "lightgrey");
      $circle->setAttribute("stroke-width", "1");
      $g->appendChild($circle);
    }}
    
    //draw a line between two points
    if (!function_exists('drawLine')) {
    function drawLine($x1, $y1, $x2, $y2) {
      global $doc, $g;

      $line = $doc->createElement("line");
      $line->setAttribute("x1", $x1);
      $line->setAttribute("y1", $y1);
      $line->setAttribute("x2", $x2);
      $line->setAttribute("y2", $y2);
      $line->setAttribute("stroke", "lightgrey");
      $line->setAttribute("stroke-width", "1");
      $g->appendChild($line);
    }}
    
    //draw scale mark on the radar
    //$x, $y: coordinates
    //$mark : text to be displayed
    if (!function_exists('drawMark')) {
    function drawMark($x, $y, $mark) {
      global $FONT_SIZE, $dx, $dy, $doc, $g;

      $text = $doc->createElement("text");
      $text->setAttribute("x", $x + $dx);
      $text->setAttribute("y", $y + $dy);
      $text->setAttribute("font-family", "Verdana");
      $text->setAttribute("font-size", $FONT_SIZE);
    
      $text->setAttribute("fill", "lightgrey");
      $text->appendChild($doc->createTextNode($mark));
      $g->appendChild($text);
    }}
    
    //draw an axis legend
    //$x, $y: coordinates
    //$element : element which title is to be displayed
    if (!function_exists('drawText')) {
    function drawText($x, $y, $element) {
      global $FONT_SIZE, $dx, $dy, $doc, $g, $lang, $f, $save;

      $text = $doc->createElement("text");
      $text->setAttribute("x", $x + $dx);
      $text->setAttribute("y", $y + $dy);
      $text->setAttribute("font-family", "Verdana");
      $text->setAttribute("font-size", $FONT_SIZE);
      $text->appendChild($doc->createTextNode($element->title));
      
      if ($element->children) {
	if($save) $text->setAttribute("fill", "black"); else $text->setAttribute("fill", "green");
	$a = $doc->createElement("a");
	$a->setAttribute("xlink:href", $_SERVER['PHP_SELF']."?lang=$lang&".$f."c=".$element->name."&svg=yes");
	$a->appendChild($text);
	$g->appendChild($a);
      } else {
	$text->setAttribute("fill", "black");
	$g->appendChild($text);
      }
      
      //text position is ajusted to be outside the circle shape
      //8 here is empiric data :)
      $textLength = strlen($element->title)*8;
      $myX = (abs($x)==$x)?$x:$x-$textLength;
      $myY = (abs($y)==$y)?$y+$FONT_SIZE:$y;
      $text->setAttribute("x", $myX + $dx);
      $text->setAttribute("y", $myY + $dy);
    }}

    //draw "Up" and "Back" links under the navigation tree
    //$name : name of the current criterion
    if (!function_exists('drawNavBar')) {
    function drawNavBar($name) {
      global $doc, $g, $msg, $myDoc, $lang, $f;

      $a = $doc->createElement("a");
      $a->setAttribute("xlink:href","show.php?lang=$lang&".$f."svg=yes");
      $text = $doc->createElement("text");
      $text->setAttribute("x", 0);
      $text->setAttribute("y", 25);
      $text->setAttribute("fill", "green");
      $text->appendChild($doc->createTextNode($msg['s5_back']));
      $a->appendChild($text);
      $g->appendChild($a);

      if ($myDoc[0]->getParent($name)) {
	$a = $doc->createElement("a");
	$a->setAttribute("xlink:href", $_SERVER['PHP_SELF']."?lang=$lang&".$f."c=".$myDoc[0]->getParent($name)->getAttribute("name")."&svg=yes");
	$text = $doc->createElement("text");
	$text->setAttribute("x", strlen($msg['s5_back'])*12);
	$text->setAttribute("y", 25);
	$text->setAttribute("fill", "green");
	$text->appendChild($doc->createTextNode($msg['s5_up']));
	$a->appendChild($text);
	$g->appendChild($a);
      }
    }}

    //draw the graph's title including software name and release and navigation tree
    //$name : name of the current criterion
    if (!function_exists('drawTitle')) {
    function drawTitle($name) {
      global $doc, $g, $FONT_SIZE, $myDoc;
    
      $text = $doc->createElement("text");
      $text->setAttribute("font-family", "Verdana");
      $text->setAttribute("font-weight", "bold");
      $text->setAttribute("font-size", $FONT_SIZE);
      
      $tspan = $doc->createElement("tspan");
      $tspan->appendChild($doc->createTextNode($title = $myDoc[0]->getkeytitle($name)));
      $text->appendChild($tspan);

      $lasttspan = $tspan;

      $node = $name;
      while ($myDoc[0]->getParent($node)) {
	$tspan = $doc->createElement("tspan");
	$tspan->appendChild($doc->createTextNode($myDoc[0]->getParent($node)->getAttribute("title") . " > "));
	$text->insertBefore($tspan, $lasttspan);
	$node = $myDoc[0]->getParent($node)->getAttribute("name");
	$lasttspan = $tspan;
      }

      for ($i=0; $i < count($myDoc); $i++) {
	$tspan = $doc->createElement("tspan");
	$tspan->setAttribute("fill", getcolor($i));
	$tspan->appendChild($doc->createTextNode($myDoc[$i]->getkey("appname")." ".$myDoc[$i]->getkey("release")." "));
	$text->insertBefore($tspan, $lasttspan);
      }
    
      return $text;
    }}
    
    //draw path between points on each axis
    //$myDoc : QSOSDocument concerned
    //$name : name of the criteria regrouping subcriteria to be displayed
    //	if $name is not set, gobal sectiosn are displayed
    //$n : position of the software to display in the list (used for coloring)
    //$weights: array of weights for the scores
    if (!function_exists('drawPath')) {
    function drawPath($myDoc, $name, $n, $weights) {
      global $doc, $SCALE, $dx, $dy;

      $path = $doc->createElement("path");
      $myD = "";
      
      if (isset($name) && $name != "") {
	$tree = $myDoc->getWeightedSubTree($name, $weights);
      } else {
	$tree = $myDoc->getWeightedTree($weights);
      }
      
      /*N
      $totalWeight = 0;
      for ($i=0; $i < count($tree); $i++) {
	$totalWeight = $totalWeight + $weights[$tree[$i]->name];
      }*/

      drawAxis(count($tree));
      $angle = 0;
      for ($i=0; $i < count($tree); $i++) {
	/*N $delta = $weights[$tree[$i]->name]*2*pi()/$totalWeight;
	$angle_text = $angle + $delta/2;
	$angle = $angle + $delta;
	drawSingleAxis($angle);*/

	$myD .= ($i==0)?"M":"L";
	//N: should be commented
	$angle = ($i+1)*2*pi()/(count($tree));
	$x = ($tree[$i]->score)*$SCALE*cos($angle);
	$x = $x + $dx;
	$y = ($tree[$i]->score)*$SCALE*sin($angle);
	$y = $y + $dy;
	$myD .= " $x $y ";
	//2.1 = 2 + 0.1 of padding before actual text display
	//N: drawText(2.1*$SCALE*cos($angle_text), 2.1*$SCALE*sin($angle_text), $tree[$i]);
	drawText(2.1*$SCALE*cos($angle), 2.1*$SCALE*sin($angle), $tree[$i]);
      }
      $myD .= "z";
      $path->setAttribute("d", $myD);
      $path->setAttribute("fill", getColor($n));
      $path->setAttribute("fill-opacity", "0.2");
      $path->setAttribute("stroke-width", "3");
      $path->setAttribute("stroke", getColor($n));
    
      return $path;
    }}
    
    //Return drawing color depending on software position in the list
    if (!function_exists('getColor')) {
    function getColor($i) {
      $colors = array('red', 'blue', 'green', 'purple');

      if($i < count($colors)) {
	return $colors[$i];
      } else {
	return "black";
      }
    }}

    $svg = $doc->createElement('svg');
    $svg->setAttribute('xmlns', 'http://www.w3.org/2000/svg');
    $svg->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    $svg->setAttribute('width', '1000');
    $svg->setAttribute('height', '600');
    
    //Graph element
    $g = $doc->createElement('g');
    $g->setAttribute('transform', 'translate(50,50)');
    $g->appendChild(drawTitle($name));
    if (!$save) drawNavBar($name);
    //display each software on the graph
    for($i=0; $i<$num; $i++) {
      $g->appendChild(drawPath($myDoc[$i], $name, $i, $weights));
    }
    $svg->appendChild($g);
    $doc->appendChild($svg);
    
    if ($save) {
      $doc->save($file);
    } else {
      echo $doc->saveXML();
    }
  }

  function showRadar() {
    global $SCALE, $dx, $dy;
    $SCALE = 70;
    $dx = 500;
    $dy = 300;

    return $this->Radar(false);
  }

  function saveRadar($file) {
    global $SCALE, $dx, $dy, $save;
    $SCALE = 50;
    $dx = 500;
    $dy = 150;
    $save = true;

    $this->Radar(true, $file);
  }

  //********************************************
  // Quadrant EXPORT methods
  //********************************************

  function Quadrant($save, $file = null) {
    global $ids, $docs, $weights, $msg, $output_quadrant;
    
    $ids = $this->ids;
    $docs = $this->docs;
    $weights = $this->weights;
    $msg = $this->msg;

    function draw($x,$y,$text,$i,$ellipsis=false) {
      global $output_quadrant, $ids;

      $g = $output_quadrant->createElement('g');
      $g->setAttribute("transform","translate($x,$y)");
      $g->setAttribute("transform","translate($x,$y)");

      $a = $output_quadrant->createElement('a');
      $a->setAttribute("xlink:href","show.php?lang=$lang&id[]=$i&svg=yes");

      if ($ellipsis) {
	$path = $output_quadrant->createElement('path');
	$path->setAttribute("style","fill:#fcdea2;fill-opacity:0.5;stroke:#000000;stroke-width:2;stroke-opacity:1");
	$path->setAttribute("d","M -57,0 A 57,21 0 1 1 57,0 A 57,21 0 1 1 -57,0 z");
	$a->appendChild($path);
	$fontSize = '10px';
      } else $fontSize = '14px';

      $t = $output_quadrant->createElement('text');
      $tspan = $output_quadrant->createElement('tspan', $text);
      $tspan->setAttribute("style","font-size:$fontSize;text-anchor:middle;font-family:Bitstream Vera Sans");
      $tspan->setAttribute("y","2.7");
      $tspan->setAttribute("x","0");
      $t->appendChild($tspan);
      $a->appendChild($t);
      $g->appendChild($a);

      return $g;
    }

    $f=fopen("export/quadrant.xml","r");
    $input = fread($f, filesize("export/quadrant.xml"));
    fclose($f); 

    $output_quadrant = new DOMDocument('1.0', 'UTF-8');

    for($i=0; $i<count($docs); $i++) {
      $tree = $docs[$i]->getWeightedTree($weights);
      $y = 340 - (($tree[0]->score)*340/2);
      $totalWeight = 0;
      $sum = 0;
      for($k=1; $k<count($tree); $k++) {
	$name = $tree[$k]->name;
	$weight = $weights[$name];
	if (!isset($weight)) $weight = 1;
	$totalWeight = $totalWeight + $weight;
	$sum += round(($tree[$k]->score)*$weight, 2);
      }
      if ($totalWeight == 0) $score = 0;
      $score = round(($sum/$totalWeight), 2);
      $x = $score*500/2;
      $output_quadrant->appendChild(draw($x, $y, $docs[$i]->getkey("appname"), $ids[$i], true));
    }

    //hack to remove XML declaration
    foreach($output_quadrant->childNodes as $node)
	$fragment .= $output_quadrant->saveXML($node)."\n";

    $insert = "<text transform='matrix(0,-1,1,0,0,0)'><tspan style='font-size:14px;font-family:Bitstream Vera Sans' y='28' x='-318'>".$msg['qq_maturity']."</tspan></text>\n";
    $insert .= "<text><tspan style='font-size:14px;font-family:Bitstream Vera Sans' y='471' x='196'>".$msg['qq_funccoverage']."</tspan></text>\n</g>\n";

    $content = $input."\n".$insert.$fragment."</g></g></g></svg>";

    if ($save) {
      $file_content = fopen($file, 'w');
      fwrite($file_content, $content);
      fclose($file_content);
    } else {
      header("Content-type: image/svg+xml");
      echo $content;
    }
  }

  function showQuadrant() {
    $this->Quadrant(false);
  }

  function saveQuadrant($file) {
    $this->Quadrant(true, $file);
  }

  //********************************************
  // ODS EXPORT methods
  //********************************************

  function ODS() {
    global $title, $subtitle, $odsfile, $numrow, $graph_formula_module, $output, $output_settings, $input, $document, $table0, $table1, $ids, $docs, $names, $weights, $msg, $lang, $template;

    $title = $this->title;
    $subtitle = $this->subtitle;
    $ids = $this->ids;
    $docs = $this->docs;
    $odsfile = "QSOS_".$docs[0]->getkey("qsosappfamily").".ods";
    $names = $this->names;
    $weights = $this->weights;
    $lang = $this->lang;
    $msg = $this->msg;
    $template = $this->template;

    function createCell($style, $type, $value, $formula=false, $validator=false) {
      global $output;

      //HACK: & caracter causes an error because of HTML entities
      $value = str_replace("&", "+", $value);

      $cell = $output->createElement('table:table-cell');
      if ($style != "") $cell->setAttribute("table:style-name",$style);
      if (!($formula)) {
	$cell->setAttribute("office:value-type",$type);
	$cell->setAttribute("office:value",$value);
	$text = $output->createElement('text:p',$value);
	$cell->appendChild($text);
      } else {
	$cell->setAttribute("table:formula",$formula);
      }
      if ($validator) {
	$cell->setAttribute("table:content-validation-name",$validator);
      }

      return $cell;
    }

    function getFormula($cells) {
      $quotient = "";
      $dividend = "";
      for ($i=0; $i < count($cells); $i++) {
	if ($i != 0) {
	  $quotient .= "+";
	  $dividend .= "+";
	}
	$quotient .= "[.C".$cells[$i]."]*[.D".$cells[$i]."]";
	$dividend .= "[.D".$cells[$i]."]";
      }
      return "oooc:=IF(($dividend)=0;0;($quotient)/($dividend))";
    }

    function createTreeCriteria($tree, $table0, $depth) {
      global $output;
      global $input;
      global $numrow;
      $children = array();

      $new_depth = $depth + 1;
      $offset = $new_depth*10;
      $idF = 0;

      switch ($depth) {
	case '0':
	  //Section
	  $style_row = 'ro1';
	  $style_title = 'ce2';
	  break;
	case '1':
	  //Level 1 criterion
	  $style_row = 'ro1';
	  $style_title = 'ce3';
	  break;
	case '2':
	  //Level 2 criterion
	  $style_row = 'ro1';
	  $style_title = '';
	  break;
	default:
	  //Level N criterion,  N > 2
	  $style_row = 'ro1';
	  $style_title = 'ce8';
	  break;
      }

      foreach($tree as $element) {
	$name = $element->name;
	$title = $element->title;
	$subtree = $element->children;
	$comment = $input->getgeneric($name, "comment");

	$numrow++;
	array_push($children, $numrow);

	//New row for first sheet (table0, criteria)
	$row = $output->createElement('table:table-row');
	$row->setAttribute("table:style-name",$style_row);
	//Criterion
	$row->appendChild(createCell($style_title, "string", $title));

	//Desc, Desc0, 1 and 2
	$row->appendChild(createCell($style_title, "string", $input->getgeneric($name, "desc")));
	$row->appendChild(createCell($style_title, "string", $input->getgeneric($name, "desc0")));
	$row->appendChild(createCell($style_title, "string", $input->getgeneric($name, "desc1")));
	$row->appendChild(createCell($style_title, "string", $input->getgeneric($name, "desc2")));
	$table0->appendChild($row);

	//Recursivity
	if ($subtree) {
	  //Subcriteria regrouping
	  $group0 = $output->createElement('table:table-row-group');
	  $return = createTreeCriteria($subtree, $group0, $new_depth);
	  $table0->appendChild($group0);
	}
      }
      return $children;
    }

    function createTreeSynthesis($tree, $table0, $depth) {
      global $output;
      global $input;
      global $numrow;
      global $ids;
      global $docs, $names;
      global $weights;
      $children = array();

      $new_depth = $depth + 1;
      $offset = $new_depth*10;
      $idF = 0;

      switch ($depth) {
	case '0':
	  //Section
	  $style_row = 'ro1';
	  $style_title = 'ce2';
	  $style_comment = 'ce2';
	  $style_score = 'ce5';
	  $style_weight = 'ce5c';
	  break;
	case '1':
	  //Level 1 criterion
	  $style_row = 'ro1';
	  $style_title = 'ce3';
	  $style_comment = 'ce3';
	  $style_score = 'ce6';
	  $style_weight = 'ce6c';
	  break;
	case '2':
	  //Level 2 criterion
	  $style_row = 'ro1';
	  $style_title = '';
	  $style_comment = '';
	  $style_score = '';
	  $style_weight = 'ce4c';
	  break;
	default:
	  //Level N criterion,  N > 2
	  $style_row = 'ro1';
	  $style_title = 'ce8';
	  $style_comment = 'ce8';
	  $style_score = 'ce9';
	  $style_weight = 'ce9c';
	  break;
      }

      foreach($tree as $element) {
	$name = $element->name;
	$title = $element->title;
	$subtree = $element->children;
	$comment = $input->getgeneric($name, "comment");

	$numrow++;
	array_push($children, $numrow);

	$row = $output->createElement('table:table-row');
	$row->setAttribute("table:style-name",$style_row);
	//Criterion
	$row->appendChild(createCell($style_title, "string", $title));
	$table0->appendChild($row);
	//Weight
	$weight = isset($weights[$name])?$weights[$name]:1;
	$row->appendChild(createCell($style_weight, "float", $weight, false, "val1"));
	//Scores
	$i = 0;
	foreach($ids as $id) {  
	  $input = $docs[$i];
	  $name = $names[$i];
	  $i++;
	  $num = $numrow + 7;
	  $row->appendChild(createCell($style_score, "string", null, "oooc:=['$name'.C$num]"));
	  $table0->appendChild($row);
	}

	//Recursivity
	if ($subtree) {
	  //Subcriteria regrouping
	  $group0 = $output->createElement('table:table-row-group');
	  $return = createTreeSynthesis($subtree, $group0, $new_depth);
	  $table0->appendChild($group0);
	}
      }
      return $children;
    }

    function createTreeEval($tree, $table1, $depth) {
      global $output;
      global $input;
      global $numrow;
      global $msg, $lang, $template;

      include("export/$template/settings-ods-$lang.php");

      $children = array();

      $new_depth = $depth + 1;
      $offset = $new_depth*10;
      $idF = 0;

      switch ($depth) {
	case '0':
	  //Section
	  $style_row = 'ro1';
	  $style_title = 'ce2';
	  $style_comment = 'ce2';
	  $style_score = 'ce5';
	  $style_weight = 'ce5';
	  break;
	case '1':
	  //Level 1 criterion
	  $style_row = 'ro1';
	  $style_title = 'ce3';
	  $style_comment = 'ce3';
	  $style_score = 'ce6';
	  $style_weight = 'ce6';
	  break;
	case '2':
	  //Level 2 criterion
	  $style_row = 'ro1';
	  $style_title = '';
	  $style_comment = '';
	  $style_score = '';
	  $style_weight = '';
	  break;
	default:
	  //Level N criterion,  N > 2
	  $style_row = 'ro1';
	  $style_title = 'ce8';
	  $style_comment = 'ce8';
	  $style_score = 'ce9';
	  $style_weight = 'ce9';
	  break;
      }

      foreach($tree as $element) {
	$name = $element->name;
	$title = $element->title;
	$subtree = $element->children;
	$comment = $input->getgeneric($name, "comment");

	$numrow++;
	array_push($children, $numrow);

	//New row for second sheet (table1, evaluation)
	$row = $output->createElement('table:table-row');
	$row->setAttribute("table:style-name",$style_row);
	//Criterion
	$row->appendChild(createCell($style_title, "string", $title));
	//Comment
	$row->appendChild(createCell($style_comment, "string", $comment));
	//Score
	$score = createCell($style_score, "float", $element->score);
	$row->appendChild($score);
	//Weight
	$num = $numrow - 7;
	$row->appendChild(createCell($style_weight, "float", null, "oooc:=['".$tpl_msg['ods_synthesis']."'.B$num]"));
	$table1->appendChild($row);

	//Recursivity
	if ($subtree) {
	  //Subcriteria regrouping
	  $group = $output->createElement('table:table-row-group');
	  $return = createTreeEval($subtree, $group, $new_depth);
	  //Set score formula
	  $score->setAttribute("table:formula",getFormula($return));
	  $table1->appendChild($group);
	}
      }
      return $children;
    }

    function createColumn($style,$styledefault) {
      global $output;
      $column = $output->createElement('table:table-column');
      $column->setAttribute("table:style-name",$style);
      $column->setAttribute("table:default-cell-style-name",$styledefault);
      return $column;
    }

    function createSimpleRow() {
      global $output;
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","Default");
      $cell->setAttribute("table:number-columns-repeated","4");
      $row->appendChild($cell);
      return $row;
    }

    function createHeaderRow($title,$value) {
      global $output;
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce2");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$title);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce8");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$value);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce10");
      $cell->setAttribute("table:number-columns-repeated","2");
      $row->appendChild($cell);
      return $row;
    }

    function createHomeDoubleRow($title,$value) {
      global $output;
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $row->appendChild($cell);      
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce13");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$title);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce17");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$value);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce1");
      $cell->setAttribute("table:number-columns-repeated","2");
      $row->appendChild($cell);
      return $row;
    }

    function createHomeItalicRow($value) {
      global $output;
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $row->appendChild($cell);      
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce16");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$value);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce1");
      $cell->setAttribute("table:number-columns-repeated","3");
      $row->appendChild($cell);
      return $row;
    }

    function createHomeUderlineRow($value) {
      global $output;
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $row->appendChild($cell);      
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce18");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$value);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce1");
      $cell->setAttribute("table:number-columns-repeated","2");
      $row->appendChild($cell);
      return $row;
    }

    function createTitleRow1() {
      global $output;
      global $msg;
      global $lang, $template;

      include("export/$template/settings-ods-$lang.php");

      $title = $tpl_msg['ods_header'];
      
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce15");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$title);
      $cell->appendChild($text);
      $row->appendChild($cell);
      
      return $row;
    }

    function createTitleRow2($title) {
      global $output;
      global $subtitle;

      if ($subtitle) $title = $subtitle." - ".$title;
      
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce14");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$title);
      $cell->appendChild($text);
      $row->appendChild($cell);
      
      return $row;
    }

    function createTitleRow3($title) {
      global $output;
      
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce13");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$title);
      $cell->appendChild($text);
      $row->appendChild($cell);
      
      return $row;
    }

    function createValidator() {
      global $output;
      global $msg;
      
      $validators = $output->createElement('table:content-validations');
      
      $validator = $output->createElement('table:content-validation');
      $validator->setAttribute("table:name","val1");
      $validator->setAttribute("table:condition","oooc:cell-content-is-whole-number() and cell-content()>=0");
      $validator->setAttribute("table:allow-empty-cell","false");
      $validator->setAttribute("table:base-cell-address",$msg['ods_synthesis']."B6");
      
      $help = $output->createElement('table:help-message');
      $help->setAttribute("table:title",$msg['ods_val_title']);
      $help->setAttribute("table:display","true");
      $text = $output->createElement('text:p',$msg['ods_val_helpmsg']);
      $help->appendChild($text);
      $validator->appendChild($help);
      
      $error = $output->createElement('table:error-message');
      $error->setAttribute("table:message-type","stop");
      $error->setAttribute("table:title",$msg['ods_val_error']);
      $error->setAttribute("table:display","true");
      $text = $output->createElement('text:p',$msg['ods_val_errormsg']);
      $error->appendChild($text);
      $validator->appendChild($error);

      $validators->appendChild($validator);

      return $validators;
    }

    function createFont($fontFamily) {
      global $output;
      $font = $output->createElement('style:font-face');
      $font->setAttribute("style:name",$fontFamily);
      $font->setAttribute("svg:font-family","'$fontFamily'");
      $font->setAttribute("style:font-pitch","variable");
      return $font;
    }

    function createColumnStyle($name,$width) {
      global $output;
      $style = $output->createElement('style:style');
      $style->setAttribute("style:name",$name);
      $style->setAttribute("style:family","table-column");
      $substyle = $output->createElement('style:table-column-properties');
      $substyle->setAttribute("fo:break-before","auto");
      $substyle->setAttribute("style:column-width",$width);
      $style->appendChild($substyle);
      return $style;
    }

    function createRowStyle($name,$height) {
      global $output;
      $style = $output->createElement('style:style');
      $style->setAttribute("style:name",$name);
      $style->setAttribute("style:family","table-row");
      $substyle = $output->createElement('style:table-row-properties');
      $substyle->setAttribute("style:row-height",$height);
      $substyle->setAttribute("fo:break-before","auto");
      $substyle->setAttribute("style:use-optimal-row-height","true");
      $style->appendChild($substyle);
      return $style;
    }

    function createCellStyle($name, $wrap, $backgroundColor, $textAlignSource, $repeatContent, $verticalALign, $textAlign, $marginLeft, $fontColor, $fontWeight, $border, $fontSize, $fontStyle, $underline, $protected = true) {
      global $output;
      $style = $output->createElement('style:style');
      $style->setAttribute("style:name",$name);
      $style->setAttribute("style:family","table-cell");
      $style->setAttribute("style:parent-style-name","Default");

      if (isset($wrap) || isset($backgroundColor) || isset($textAlignSource) || isset($repeatContent) || isset($verticalALign) || isset($border)) {
	$substyle = $output->createElement('style:table-cell-properties');
	if (isset($wrap)) $substyle->setAttribute("fo:wrap-option",$wrap);
	if (isset($backgroundColor)) $substyle->setAttribute("fo:background-color",$backgroundColor);
	if (isset($border)) $substyle->setAttribute("fo:border","0.002cm solid #000000");
	if (isset($textAlignSource)) $substyle->setAttribute("style:text-align-source",$textAlignSource);
	if (isset($repeatContent)) $substyle->setAttribute("style:repeat-content",$repeatContent);
	if (isset($verticalALign)) $substyle->setAttribute("style:vertical-align",$verticalALign);
	if (!$protected) {
	  $substyle->setAttribute("style:cell-protect","none");
	  $substyle->setAttribute("fo:border","0.06pt double #ff950e");
	  $substyle->setAttribute("style:border-line-width","0.0008in 0.0138in 0.0008in");
	}
	$style->appendChild($substyle);
      }

      if (isset($textAlign) || isset($marginLeft)) {
	$substyle = $output->createElement('style:paragraph-properties');
	if (isset($textAlign)) $substyle->setAttribute("fo:text-align",$textAlign);
	if (isset($marginLeft)) $substyle->setAttribute("fo:margin-left",$marginLeft);
	$style->appendChild($substyle);
      }

      if (isset($fontColor) || isset($fontWeight) || isset($fontSize) || isset($fontStyle) || isset($underline)) {
	$substyle = $output->createElement('style:text-properties');
	if (isset($fontColor)) $substyle->setAttribute("fo:color",$fontColor);
	if (isset($fontWeight)) $substyle->setAttribute("fo:font-weight",$fontWeight);
	if (isset($fontSize)) $substyle->setAttribute("fo:font-size",$fontSize."pt");
	if (isset($fontStyle)) $substyle->setAttribute("fo:font-style",$fontStyle);
	if (isset($underline)) {
	  $substyle->setAttribute("style:text-underline-style","solid");
	  $substyle->setAttribute("style:text-underline-width","auto");
	  $substyle->setAttribute("style:text-underline-color","font-color");
	}
	$style->appendChild($substyle);
      }

      return $style;
    }

    function initSynthesisSheet() {
      global $output;
      global $input;
      global $table0;
      global $msg;
      global $ids;
      global $docs, $names, $lang, $template;

      include("export/$template/settings-ods-$lang.php");


      $table0 = $output->createElement('table:table');
      $table0->setAttribute("table:name",$tpl_msg['ods_synthesis']);
      $table0->setAttribute("table:style-name","ta1");
      $table0->setAttribute("table:print","false");
      $table0->setAttribute("table:protected","true");

      $protection = $output->createElement('table:table-protection');
      $protection->setAttribute("table:select-protected-cells","true");
      $protection->setAttribute("table:select-unprotected-cells","true");
      $table0->appendChild($protection);

      $table0->appendChild(createColumn("co0","ce4"));
      $table0->appendChild(createColumn("co4","ce7"));

      foreach($ids as $id) {
	$table0->appendChild(createColumn("co0","ce7"));
      }

      //Title
      $table0->appendChild(createTitleRow1());
      $table0->appendChild(createTitleRow2($input->getkey("qsosappfamily")));
      $table0->appendChild(createTitleRow3($tpl_msg['ods_synthesis_title']));

      $table0->appendChild(createSimpleRow());
      
      //Note on weight modification
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce16");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_note_weight']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $table0->appendChild($row);
      
      $table0->appendChild(createSimpleRow());

      //Criteria
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce11");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_criterion']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      
      //Weight
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce12");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_weight']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      
      //Evaluations
      $i = 0;
      foreach($ids as $id) {  
	$input = $docs[$i];
	$name = $names[$i];
	$i++;
	$cell = $output->createElement('table:table-cell');
	$cell->setAttribute("table:style-name","ce12");
	$cell->setAttribute("office:value-type","string");
	$text = $output->createElement('text:p',$name);
	$cell->appendChild($text);
	$row->appendChild($cell);
      }
      
      $table0->appendChild($row);
    }

    function initCriteriaSheet() {
      global $output;
      global $input;
      global $table0;
      global $msg, $lang, $template;

      include("export/$template/settings-ods-$lang.php");

      //First sheet (Criteria)
      $table0 = $output->createElement('table:table');
      $table0->setAttribute("table:name",$tpl_msg['ods_criteria']);
      $table0->setAttribute("table:style-name","ta1");
      $table0->setAttribute("table:print","false");
      $table0->setAttribute("table:protected","true");

      $protection = $output->createElement('table:table-protection');
      $protection->setAttribute("table:select-protected-cells","true");
      $protection->setAttribute("table:select-unprotected-cells","true");
      $table0->appendChild($protection);

      $table0->appendChild(createColumn("co0","ce4"));
      $table0->appendChild(createColumn("co0","ce4"));
      $table0->appendChild(createColumn("co0","ce4"));
      $table0->appendChild(createColumn("co0","ce4"));
      $table0->appendChild(createColumn("co0","ce4"));

      //Title
      $table0->appendChild(createTitleRow1());
      $table0->appendChild(createTitleRow2($input->getkey("qsosappfamily")));
      $table0->appendChild(createTitleRow3($tpl_msg['ods_citeria_title']));

      $table0->appendChild(createSimpleRow());

      //QSOS version
      $table0->appendChild(createHeaderRow($tpl_msg['ods_qsosversion'],$input->getkey("qsosformat")));

      //Template version
      $table0->appendChild(createHeaderRow($tpl_msg['ods_templateversion'],$input->getkey("qsosspecificformat")));

      $table0->appendChild(createSimpleRow());

      //Criteria
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce11");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_criterion']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce11");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_desc']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce12");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_score0']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce12");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_score1']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce12");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_score2']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $table0->appendChild($row);
    }

    function initEvaluationSheet($title) {
      global $output;
      global $input;
      global $table1;
      global $msg, $lang, $template;

      include("export/$template/settings-ods-$lang.php");

      //Second sheet (Evaluation)
      $table1 = $output->createElement('table:table');
      $table1->setAttribute("table:name",$title);
      $table1->setAttribute("table:style-name","ta1");
      $table1->setAttribute("table:print","false");
      $table1->setAttribute("table:protected","true");

      $protection = $output->createElement('table:table-protection');
      $protection->setAttribute("table:select-protected-cells","true");
      $protection->setAttribute("table:select-unprotected-cells","true");
      $table1->appendChild($protection);

      $table1->appendChild(createColumn("co1","ce4"));
      $table1->appendChild(createColumn("co2","ce4"));
      $table1->appendChild(createColumn("co3","ce7"));
      $table1->appendChild(createColumn("co4","ce7"));

      //Title
      $header = $tpl_msg['ods_evaluation_title'].$input->getkey("appname")." ".$input->getkey("release");
      $table1->appendChild(createTitleRow1());
      $table1->appendChild(createTitleRow2($input->getkey("qsosappfamily")));
      $table1->appendChild(createTitleRow3($header));

      $table1->appendChild(createSimpleRow());

      //Header
      //Application
      $table1->appendChild(createHeaderRow($tpl_msg['ods_application'],$input->getkey("appname")));

      //Release
      $table1->appendChild(createHeaderRow($tpl_msg['ods_release'],$input->getkey("release")));

      //License
      $table1->appendChild(createHeaderRow($tpl_msg['ods_license'],$input->getkey("licensedesc")));

      //Url
      $table1->appendChild(createHeaderRow($tpl_msg['ods_website'],$input->getkey("url")));

      //Description
      $table1->appendChild(createHeaderRow($tpl_msg['ods_description'],$input->getkey("desc")));

      //Authors
      $authors = $input->getauthors();
      $list = "";
      for ($i=0; $i < count($authors); $i++) {
	if ($i != 0) {
	  $list .= ", ";
	}
	$list .= $authors[$i]->name." (".$authors[$i]->email.")";
      }
      $table1->appendChild(createHeaderRow($tpl_msg['ods_authors'],$list));

      //Creation date
      $table1->appendChild(createHeaderRow($tpl_msg['ods_creationdate'],$input->getkey("creation")));

      //Validation date
      $table1->appendChild(createHeaderRow($tpl_msg['ods_validationdate'],$input->getkey("validation")));

      $table1->appendChild(createSimpleRow());

      //Criteria
      $row = $output->createElement('table:table-row');
      $row->setAttribute("table:style-name","ro1");
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce11");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_criterion']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce11");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_comment']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce12");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_score']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $cell = $output->createElement('table:table-cell');
      $cell->setAttribute("table:style-name","ce12");
      $cell->setAttribute("office:value-type","string");
      $text = $output->createElement('text:p',$tpl_msg['ods_weight']);
      $cell->appendChild($text);
      $row->appendChild($cell);
      $table1->appendChild($row);
    }

    function initDocument() {
      global $output;

      //MAIN DOCUMENT ELEMENT
      $document = $output->createElement('office:document-content');
      $document->setAttribute("xmlns:office","urn:oasis:names:tc:opendocument:xmlns:office:1.0");
      $document->setAttribute("xmlns:style","urn:oasis:names:tc:opendocument:xmlns:style:1.0");
      $document->setAttribute("xmlns:text","urn:oasis:names:tc:opendocument:xmlns:text:1.0");
      $document->setAttribute("xmlns:table","urn:oasis:names:tc:opendocument:xmlns:table:1.0");
      $document->setAttribute("xmlns:draw","urn:oasis:names:tc:opendocument:xmlns:drawing:1.0");
      $document->setAttribute("xmlns:fo","urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0");
      $document->setAttribute("xmlns:xlink","http://www.w3.org/1999/xlink");
      $document->setAttribute("xmlns:dc","http://purl.org/dc/elements/1.1/");
      $document->setAttribute("xmlns:meta","urn:oasis:names:tc:opendocument:xmlns:meta:1.0");
      $document->setAttribute("xmlns:number","urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0");
      $document->setAttribute("xmlns:svg","urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0");
      $document->setAttribute("xmlns:chart","urn:oasis:names:tc:opendocument:xmlns:chart:1.0");
      $document->setAttribute("xmlns:dr3d","urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0");
      $document->setAttribute("xmlns:math","http://www.w3.org/1998/Math/MathML");
      $document->setAttribute("xmlns:form","urn:oasis:names:tc:opendocument:xmlns:form:1.0");
      $document->setAttribute("xmlns:script","urn:oasis:names:tc:opendocument:xmlns:script:1.0");
      $document->setAttribute("xmlns:ooo","http://openoffice.org/2004/office");
      $document->setAttribute("xmlns:ooow","http://openoffice.org/2004/writer");
      $document->setAttribute("xmlns:oooc","http://openoffice.org/2004/calc");
      $document->setAttribute("xmlns:dom","http://www.w3.org/2001/xml-events");
      $document->setAttribute("xmlns:xforms","http://www.w3.org/2002/xforms");
      $document->setAttribute("xmlns:xsd","http://www.w3.org/2001/XMLSchema");
      $document->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
      $document->setAttribute("office:version","1.2");

      //FONT DECLARATIONS
      $fontfaces = $output->createElement('office:font-face-decls');
      $fontfaces->appendChild(createFont("Lucida Sans Unicode"));
      $fontfaces->appendChild(createFont("Tahoma"));
      $fontfaces->appendChild(createFont("Arial"));
      $document->appendChild($fontfaces);

      //STYLE DECLARATIONS
      $styles = $output->createElement('office:automatic-styles');
      //Column styles
      $styles->appendChild(createColumnStyle("co0","4.717cm"));
      $styles->appendChild(createColumnStyle("co1","5.117cm"));
      $styles->appendChild(createColumnStyle("co2","10.931cm"));
      $styles->appendChild(createColumnStyle("co3","1.452cm"));
      $styles->appendChild(createColumnStyle("co4","1.452cm"));
      //Row styles
      $styles->appendChild(createRowStyle("ro1","0.453cm"));
      $styles->appendChild(createRowStyle("ro2","0.453cm"));
      //ta1: basic table
      $style = $output->createElement('style:style');
      $style->setAttribute("style:name","ta1");
      $style->setAttribute("style:family","table");
      $style->setAttribute("style:master-page-name","Default");
      $substyle = $output->createElement('style:table-properties');
      $substyle->setAttribute("table:display","true");
      $substyle->setAttribute("style:writing-mode","lr-tb");
      $style->appendChild($substyle);
      $styles->appendChild($style);
      //Cell styles
      $styles->appendChild(createCellStyle("ce1", "wrap", null, null, null, "middle", null, null, "#ffffff", null, null, null, null, null, true));
      $styles->appendChild(createCellStyle("ce2", "wrap", "#2323dc", null, null, "middle", null, null, "#ffffff", "bold", true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce3", "wrap", "#99ccff", null, null, "middle", null, null, null, null, true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce4","wrap","#ccffff", null, null,"middle", null, null, null, null, true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce4c","wrap","#ccffff", null, null,"middle", "center", null, null, null, true, null, null, null, false));
      $styles->appendChild(createCellStyle("ce5", null, "#2323dc", "fix", "false", "middle", "center", "0cm", "#ffffff", "bold", true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce5c", null, "#2323dc", "fix", "false", "middle", "center", "0cm", "#ffffff", "bold", true, null, null, null, false));
      $styles->appendChild(createCellStyle("ce6", null, "#99ccff", "fix", "false", "middle", "center", "0cm", null, null, true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce6c", null, "#99ccff", "fix", "false", "middle", "center", "0cm", null, null, true, null, null, null, false));
      $styles->appendChild(createCellStyle("ce7", null, "#ccffff", "fix", "false", "middle", "center", "0cm", null, null, true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce8", "wrap", null, "fix", "false", "middle", null, null, null, null, true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce9", null, null, "fix", "false", "middle", "center", "0cm", null, null, true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce9c", null, null, "fix", "false", "middle", "center", "0cm", null, null, true, null, null, null, false));
      $styles->appendChild(createCellStyle("ce10", "wrap", null, "fix", "false", "middle", null, null, null, null, null, null, null, null, true));
      $styles->appendChild(createCellStyle("ce11", "wrap", "#000000", null, null, "middle", null, null, "#ffffff", "bold", true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce12", "wrap", "#000000", null, null, "middle", "center", null, "#ffffff", "bold", true, null, null, null, true));
      $styles->appendChild(createCellStyle("ce13", null, null, null, null, "middle", null, null, "#000000", "bold", null, null, null, null, true));
      $styles->appendChild(createCellStyle("ce14", null, null, null, null, "middle", null, null, "#000000", "bold", null, 12, null, null, true));
      $styles->appendChild(createCellStyle("ce15", null, null, null, null, "middle", null, null, "#000000", "bold", null, 14, null, null, true));
      $styles->appendChild(createCellStyle("ce16", null, null, null, null, "middle", null, null, "#000000", null, null, null, "italic", null, true));
      $styles->appendChild(createCellStyle("ce17", null, null, null, null, "middle", null, null, "#000000", null, null, null, null, null, true));
      $styles->appendChild(createCellStyle("ce18", null, null, null, null, "middle", null, null, "#000000", "bold", null, null, null, true, true));
      $document->appendChild($styles);
      
      return $document;
    }

    function createMapEntry($name, $split) {
      global $output_settings;

      $table = $output_settings->createElement('config:config-item-map-entry');  
      $table->setAttribute("config:name",$name);

      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","CursorPositionX");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","CursorPositionY");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","HorizontalSplitMode");
      $config_item->setAttribute("config:type","short");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 2);
      $config_item->setAttribute("config:name","VerticalSplitMode");
      $config_item->setAttribute("config:type","short");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","HorizontalSplitPosition");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', $split);
      $config_item->setAttribute("config:name","VerticalSplitPosition");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","ActiveSplitRange");
      $config_item->setAttribute("config:type","short");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","PositionLeft");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","PositionRight");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","PositionTop");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', $split);
      $config_item->setAttribute("config:name","PositionBottom");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","ZoomType");
      $config_item->setAttribute("config:type","short");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 100);
      $config_item->setAttribute("config:name","ZoomValue");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 60);
      $config_item->setAttribute("config:name","PageViewZoomValue");
      $config_item->setAttribute("config:type","int");
      $table->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowGrid");
      $config_item->setAttribute("config:type","boolean");
      $table->appendChild($config_item);

      return $table;
    }

    function createSettings() {
      global $output_settings, $ids, $docs, $names, $msg, $input, $lang, $template;

      include("export/$template/settings-ods-$lang.php");

      //SETTINGS
      $settings = $output_settings->createElement('office:document-settings');
      $settings->setAttribute("xmlns:office","urn:oasis:names:tc:opendocument:xmlns:office:1.0");
      $settings->setAttribute("xmlns:xlink","http://www.w3.org/1999/xlink");
      $settings->setAttribute("xmlns:config","urn:oasis:names:tc:opendocument:xmlns:config:1.0");
      $settings->setAttribute("xmlns:ooo","http://openoffice.org/2004/office");
      $settings->setAttribute("office:version","1.2");

      $office_settings = $output_settings->createElement('office:settings');

      $view_settings = $output_settings->createElement('config:config-item-set');
      $view_settings->setAttribute("config:name","ooo:view-settings");

      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","VisibleAreaTop");
      $config_item->setAttribute("config:type","int");
      $view_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","VisibleAreaLeft");
      $config_item->setAttribute("config:type","int");
      $view_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 2257);
      $config_item->setAttribute("config:name","VisibleAreaWidth");
      $config_item->setAttribute("config:type","int");
      $view_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 4268);
      $config_item->setAttribute("config:name","VisibleAreaHeight");
      $config_item->setAttribute("config:type","int");
      $view_settings->appendChild($config_item);

      $views = $output_settings->createElement('config:config-item-map-indexed');
      $views->setAttribute("config:name","Views");

      $map_entry = $output_settings->createElement('config:config-item-map-entry');  
      $view1 = $output_settings->createElement('config:config-item', 'view1');  
      $view1->setAttribute("config:name","ViewId");
      $view1->setAttribute("config:type","string");
      $map_entry->appendChild($view1);

      $tables = $output_settings->createElement('config:config-item-map-named');  
      $tables->setAttribute("config:name","Tables");

      //Freeze of synthesis sheet
      $tables->appendChild(createMapEntry($tpl_msg['ods_synthesis'], 7));

      //Freeze of criteria sheet
      $tables->appendChild(createMapEntry($tpl_msg['ods_criteria'], 8));

      //Freeze of evaluation sheets
      $i = 0;
      foreach($ids as $id) {  
	$input = $docs[$i];
	$name = $names[$i];
	$i++;
	$tables->appendChild(createMapEntry($name, 14));
      }

      $map_entry->appendChild($tables);

      $config_item = $output_settings->createElement('config:config-item', $tpl_msg['ods_home']);
      $config_item->setAttribute("config:name","ActiveTable");
      $config_item->setAttribute("config:type","string");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 270);
      $config_item->setAttribute("config:name","HorizontalScrollbarWidth");
      $config_item->setAttribute("config:type","int");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","ZoomType");
      $config_item->setAttribute("config:type","short");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 100);
      $config_item->setAttribute("config:name","ZoomValue");
      $config_item->setAttribute("config:type","int");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 60);
      $config_item->setAttribute("config:name","PageViewZoomValue");
      $config_item->setAttribute("config:type","int");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","ShowPageBreakPreview");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowZeroValues");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowNotes");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowGrid");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 12632256);
      $config_item->setAttribute("config:name","GridColor");
      $config_item->setAttribute("config:type","long");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowPageBreaks");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","HasColumnRowHeaders");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","HasSheetTabs");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","IsOutlineSymbolsSet");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","IsSnapToRaster");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","RasterIsVisible");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 1270);
      $config_item->setAttribute("config:name","RasterResolutionX");
      $config_item->setAttribute("config:type","int");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 1270);
      $config_item->setAttribute("config:name","RasterResolutionY");
      $config_item->setAttribute("config:type","int");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 1);
      $config_item->setAttribute("config:name","RasterSubdivisionX");
      $config_item->setAttribute("config:type","int");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 1);
      $config_item->setAttribute("config:name","RasterSubdivisionY");
      $config_item->setAttribute("config:type","int");
      $map_entry->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","IsRasterAxisSynchronized");
      $config_item->setAttribute("config:type","boolean");
      $map_entry->appendChild($config_item);

      $views->appendChild($map_entry);
      $view_settings->appendChild($views);
      $office_settings->appendChild($view_settings);

      $conf_settings = $output_settings->createElement('config:config-item-set');
      $conf_settings->setAttribute("config:name","ooo:configuration-settings");

      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","IsKernAsianPunctuation");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","UpdateFromTemplate");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","LoadReadonly");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","IsDocumentShared");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","AutoCalculate");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item');
      $config_item->setAttribute("config:name","PrinterSetup");
      $config_item->setAttribute("config:type","base64Binary");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","RasterIsVisible");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item');
      $config_item->setAttribute("config:name","PrinterName");
      $config_item->setAttribute("config:type","string");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 1270);
      $config_item->setAttribute("config:name","RasterResolutionY");
      $config_item->setAttribute("config:type","int");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","IsRasterAxisSynchronized");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 0);
      $config_item->setAttribute("config:name","CharacterCompressionType");
      $config_item->setAttribute("config:type","short");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 1270);
      $config_item->setAttribute("config:name","RasterResolutionX");
      $config_item->setAttribute("config:type","int");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","IsSnapToRaster");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","HasColumnRowHeaders");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 1);
      $config_item->setAttribute("config:name","RasterSubdivisionX");
      $config_item->setAttribute("config:type","int");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 12632256);
      $config_item->setAttribute("config:name","GridColor");
      $config_item->setAttribute("config:type","long");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowZeroValues");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 1);
      $config_item->setAttribute("config:name","RasterSubdivisionY");
      $config_item->setAttribute("config:type","int");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'false');
      $config_item->setAttribute("config:name","SaveVersionOnClose");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowPageBreaks");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowGrid");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","IsOutlineSymbolsSet");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","AllowPrintJobCancel");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ApplyUserData");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 3);
      $config_item->setAttribute("config:name","LinkUpdateMode");
      $config_item->setAttribute("config:type","short");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","ShowNotes");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);
      $config_item = $output_settings->createElement('config:config-item', 'true');
      $config_item->setAttribute("config:name","HasSheetTabs");
      $config_item->setAttribute("config:type","boolean");
      $conf_settings->appendChild($config_item);

      $office_settings->appendChild($conf_settings);
      $settings->appendChild($office_settings);

      return $settings;
    }

    function createHomeSheet() {
      global $docs, $ids, $names;
      global $table0;
      global $output, $input;
      global $msg, $lang, $template;

      include("export/$template/settings-ods-$lang.php");

      //First sheet (Criteria)
      $table0 = $output->createElement('table:table');
      $table0->setAttribute("table:name",$tpl_msg['ods_home']);
      $table0->setAttribute("table:style-name","ta1");
      $table0->setAttribute("table:print","false");
      $table0->setAttribute("table:protected","true");

      $protection = $output->createElement('table:table-protection');
      $protection->setAttribute("table:select-protected-cells","true");
      $protection->setAttribute("table:select-unprotected-cells","true");
      $table0->appendChild($protection);

      $table0->appendChild(createColumn("co4","ce1"));
      $table0->appendChild(createColumn("co0","ce1"));
      $table0->appendChild(createColumn("co0","ce1"));
      $table0->appendChild(createColumn("co0","ce1"));
      $table0->appendChild(createColumn("co0","ce1"));

      //Title
      $table0->appendChild(createTitleRow1());
      $table0->appendChild(createTitleRow2($input->getkey("qsosappfamily")));
      $table0->appendChild(createTitleRow3($tpl_msg['ods_home_subtitle']));

      $table0->appendChild(createSimpleRow());
      $table0->appendChild(createSimpleRow());

      //Text
      $table0->appendChild(createHomeDoubleRow("Version", "1.0"));
      $table0->appendChild(createHomeDoubleRow("Date", date('d/m/Y')));

      $table0->appendChild(createSimpleRow());
      $table0->appendChild(createSimpleRow());

      $table0->appendChild(createHomeUderlineRow($tpl_msg['ods_home_text1']));

      $table0->appendChild(createHomeDoubleRow($tpl_msg['ods_home'], $tpl_msg['ods_home_text2']));
      $table0->appendChild(createHomeDoubleRow($tpl_msg['ods_synthesis'], $tpl_msg['ods_home_text3']));
      $table0->appendChild(createHomeDoubleRow($tpl_msg['ods_criteria'], $tpl_msg['ods_home_text4']));

      $i = 0;
      foreach($ids as $id) {  
	$input = $docs[$i];
	$title = $names[$i];
	$i++;
	$value = $tpl_msg['ods_home_text6'].$title;
	$table0->appendChild(createHomeDoubleRow($title, $value));
      }

      $table0->appendChild(createSimpleRow());
      $table0->appendChild(createSimpleRow());

      $table0->appendChild(createHomeUderlineRow("NB :"));
      $table0->appendChild(createHomeItalicRow($tpl_msg['ods_home_text5']));

      if($tpl_msg['ods_home_license']) {
	$table0->appendChild(createSimpleRow());
	$table0->appendChild(createSimpleRow());
	$table0->appendChild(createHomeUderlineRow($tpl_msg['ods_home_license']));
      }
    }

    $output = new DOMDocument();
    $output_settings = new DOMDocument('1.0', 'UTF-8');
    
    //Init document
    $document = initDocument();
    $body = $output->createElement('office:body');
    $spreadsheet = $output->createElement('office:spreadsheet');

    //Create settings
    $settings = createSettings();
    $output_settings->appendChild($settings);
    
    //Validator for weight values
    $spreadsheet->appendChild(createValidator());

    //Home sheet
    $input = $docs[0];
    createHomeSheet();
    $spreadsheet->appendChild($table0);
    
    //Synthesis Sheet
    initSynthesisSheet();
    $numrow = 7; //Reinit row counter
    createTreeSynthesis($input->getTree(), $table0, 0);
    $spreadsheet->appendChild($table0);
    
    //Criteria Sheet
    initCriteriaSheet();
    createTreeCriteria($input->getTree(), $table0, 0);
    $spreadsheet->appendChild($table0);

    //Evaluation Sheets
    $i = 0;
    foreach($ids as $id) {  
      $input = $docs[$i];
      $name = $names[$i];
      $i++;
      initEvaluationSheet($name);
      $numrow = 14; //Reinit row counter
      createTreeEval($input->getTree(), $table1, 0);
      $spreadsheet->appendChild($table1);
    }
    
    //Finalize Document (in memory)
    $body->appendChild($spreadsheet);
    $document->appendChild($body);
    $output->appendChild($document);

    //Finalize Document (on disk)
    $tempdir = $this->temp.uniqid();
    mkdir($tempdir, 0755);
    $output->save("$tempdir/content.xml");
    $output_settings->save("$tempdir/settings.xml");

    copy("export/$template/template_ods.zip", "odf/$odsfile");

    include('libs/pclzip.lib.php');
    $oofile = new PclZip("odf/$odsfile");
    $v_list = $oofile->add("$tempdir/content.xml", PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 01: ODS generation ".$oofile->errorInfo(true));
    }
    $v_list = $oofile->add("$tempdir/settings.xml", PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 02: ODS generation ".$oofile->errorInfo(true));
    }


  //Return ODS file to the browser
  header("Location: odf/$odsfile");
  exit;

  }


  //********************************************
  // ODP EXPORT methods
  //********************************************

  function ODP() {
    global $title, $subtitle, $odpfile, $document, $ids, $docs, $files, $names, $output_pages, $output_manifest, $tempdir, $msg, $lang, $template;

    $title = $this->title;
    $subtitle = $this->subtitle;
    $ids = $this->ids;
    $docs = $this->docs;
    $files = $this->files;
    $odpfile = "QSOS_".$docs[0]->getkey("qsosappfamily").".odp";
    $names = $this->names;
    $lang = $this->lang;
    $msg = $this->msg;
    $template = $this->template;

    function drawTabTitle($text) {
      global $output_pages;

      $frame = $output_pages->createElement('draw:frame');
      $frame->setAttribute("draw:style-name","Onglet");
      $frame->setAttribute("draw:text-style-name","P7");
      $frame->setAttribute("draw:layer","layout");
      $frame->setAttribute("svg:width","2.665cm");
      $frame->setAttribute("svg:height","0.742cm");
      $frame->setAttribute("draw:transform","rotate (1.5707963267949) translate (0.279cm 4.983cm)");

      $textbox = $output_pages->createElement('draw:text-box');
      $p = $output_pages->createElement('text:p');
      $span =  $output_pages->createElement('text:span', $text);
      $span->setAttribute("text:style-name","T7");  
      $p->appendChild($span);
      $textbox->appendChild($p);
      $frame->appendChild($textbox);

      return $frame;
    }

    function drawPresentationTextbox($presentation_style, $p_style, $span_style, $width, $height, $x, $y, $class, $text) {
      global $output_pages;

      $frame = $output_pages->createElement('draw:frame');
      $frame->setAttribute("presentation:style-name",$presentation_style);
      $frame->setAttribute("draw:layer","layout");
      $frame->setAttribute("svg:width",$width);
      $frame->setAttribute("svg:height",$height);
      $frame->setAttribute("svg:x",$x);
      $frame->setAttribute("svg:y",$y);
      $frame->setAttribute("presentation:class",$class);
      $frame->setAttribute("presentation:user-transformed","true");
      $textbox = $output_pages->createElement('draw:text-box');
      $p = $output_pages->createElement('text:p');
      if ($p_style) $p->setAttribute("text:style-name",$p_style);
      $span =  $output_pages->createElement('text:span', $text);
      if ($span_style) $span->setAttribute("text:style-name",$span_style);  
      $p->appendChild($span);
      $textbox->appendChild($p);
      $frame->appendChild($textbox);

      return $frame;
    }

    function drawPageTitle($text) { 
      return drawPresentationTextbox("pr4", "P6", "", "20cm", "1.5cm", "1.4cm", "2.25cm", "title", $text);
    }

    function drawAgendaList($frame_style, $frame_text_style, $L1_text_style, $L1_span_style, $L2_text_style, $L2_span_style, $width, $height, $x, $y, $text) {
      global $output_pages;

      $frame = $output_pages->createElement('draw:frame');
      $frame->setAttribute("draw:style-name",$frame_style);
      $frame->setAttribute("draw:text-style-name",$frame_text_style);
      $frame->setAttribute("draw:layer","layout");
      $frame->setAttribute("svg:width",$width);
      $frame->setAttribute("svg:height",$height);
      $frame->setAttribute("svg:x",$x);
      $frame->setAttribute("svg:y",$y);  

      $textbox = $output_pages->createElement('draw:text-box');

      $L1 = $output_pages->createElement('text:list');
      $L1->setAttribute("text:style-name",$L1_text_style);
      $item = $output_pages->createElement('text:list-item');
      $p = $output_pages->createElement('text:p');
      $span =  $output_pages->createElement('text:span', $text[0]);
      $span->setAttribute("text:style-name",$L1_span_style);  
      $p->appendChild($span);
      $item->appendChild($p);
      $L1->appendChild($item);
      $textbox->appendChild($L1);

      $L2 = $output_pages->createElement('text:list');
      if ($L2_text_style != "") $L2->setAttribute("text:style-name",$L2_text_style);
      $subitem = $output_pages->createElement('text:list-item');
      $sublist = $output_pages->createElement('text:list');
      for($i=1; $i < count($text); $i++) {
	$item = $output_pages->createElement('text:list-item');
	$p = $output_pages->createElement('text:p');
	$span =  $output_pages->createElement('text:span', $text[$i]);
	$span->setAttribute("text:style-name",$L2_span_style);  
	$p->appendChild($span);
	$item->appendChild($p);
	$sublist->appendChild($item);
      }
      $subitem->appendChild($sublist);
      $L2->appendChild($subitem);
      $textbox->appendChild($L2);
      $frame->appendChild($textbox);

      return $frame;
    }

    function createFirstPage($title, $subtitle) {
      global $output_pages, $odpfile;

      $page = $output_pages->createElement('draw:page');
      $page->setAttribute("draw:name","page1");
      $page->setAttribute("draw:style-name","dp1");
      $page->setAttribute("draw:master-page-name","SLL_5f_MOD_5f_Pre_5f_Modele_5f_de_5f_presentation");
      $page->setAttribute("presentation:presentation-page-layout-name","AL1T0");
      $page->setAttribute("presentation:use-footer-name","ftr1");
      $page->setAttribute("presentation:use-date-time-name","dtd1");

      $forms = $output_pages->createElement('office:forms');
      $forms->setAttribute("form:automatic-focus","false");
      $forms->setAttribute("form:apply-design-mode","false");
      $page->appendChild($forms);

      //Title
      $page->appendChild(drawPresentationTextbox("pr1", "", "", "27.763cm", "1.4cm", "-0.263cm", "10cm", "title", $title));
      //Subtitle
      //$page->appendChild(drawPresentationTextbox("pr2", "P1", "T1", "27cm", "1.4cm", "0.5cm", "11.5cm", "subtitle", $subtitle));

      return $page;
    }

    function createAgendaPage($name) {
      global $output_pages, $names, $lang, $msg, $template;

      include("export/$template/settings-odp-$lang.php");

      $page = $output_pages->createElement('draw:page');
      $page->setAttribute("draw:name",$name);
      $page->setAttribute("draw:style-name","dp1");
      $page->setAttribute("draw:master-page-name","Sommaire");
      $page->setAttribute("presentation:presentation-page-layout-name","AL2T1");
      $page->setAttribute("presentation:use-footer-name","ftr1");
      $page->setAttribute("presentation:use-date-time-name","dtd1");

      $forms = $output_pages->createElement('office:forms');
      $forms->setAttribute("form:automatic-focus","false");
      $forms->setAttribute("form:apply-design-mode","false");
      $page->appendChild($forms);

      //Title
      $page->appendChild(drawPresentationTextbox("pr4", "P6", "", "20cm", "1.5cm", "1.4cm", "2.25cm", "title", $tpl_msg['odp_agenda_title']));
      //Tab title
      $page->appendChild(drawTabTitle($tpl_msg['odp_agenda_title']));

      //First line
      $line = $output_pages->createElement('draw:line');
      $line->setAttribute("draw:style-name","gr3");
      $line->setAttribute("draw:text-style-name","P4");
      $line->setAttribute("draw:layer","layout");
      $line->setAttribute("svg:x1","4.227cm");
      $line->setAttribute("svg:y1","5.518cm");
      $line->setAttribute("svg:x2","4.227cm");
      $line->setAttribute("svg:y2","19.109cm");
      $p = $output_pages->createElement('text:p');
      $line->appendChild($p);
      $page->appendChild($line);

      //Second line
      $line = $output_pages->createElement('draw:line');
      $line->setAttribute("draw:style-name","gr3");
      $line->setAttribute("draw:text-style-name","P4");
      $line->setAttribute("draw:layer","layout");
      $line->setAttribute("svg:x1","15.727cm");
      $line->setAttribute("svg:y1","5.518cm");
      $line->setAttribute("svg:x2","15.727cm");
      $line->setAttribute("svg:y2","19.109cm");
      $p = $output_pages->createElement('text:p');
      $line->appendChild($p);
      $page->appendChild($line);

      //Content
      $page->appendChild(drawAgendaList("gr4", "P5", "L2", "T3", "L2", "T4", "10cm", "4.5cm", "3.5cm", "5cm", $tpl_msg['odp_agenda_introduction']));

      $text = $tpl_msg['odp_agenda_solutions'];
      foreach($names as $name) {
	array_push($text, $name);
      }

      $page->appendChild(drawAgendaList("gr5", "P5", "L4", "T5", "L4", "T4", "10cm", "4.5cm", "3.5cm", "11.7cm", $text));

      $page->appendChild(drawAgendaList("gr6", "P5", "L5", "T8", "L5", "T4", "12cm", "4.5cm", "15cm", "5cm", $tpl_msg['odp_agenda_synthesis']));

      if($tpl_msg['odp_agenda_reco']) $page->appendChild(drawAgendaList("grV", "P5", "LV", "TV", "LV", "T4", "12cm", "4.5cm", "15cm", "11.7cm", $tpl_msg['odp_agenda_reco']));

      return $page;
    }

    function getList($element, $style) {
      global $output_pages;

      $list = $output_pages->createElement('text:list');
      $list->setAttribute("text:style-name",$style);
      $item = $output_pages->createElement('text:list-item');

      if (is_array($element)) {
	foreach($element as $subelement) {
	  $item->appendChild(getList($subelement, $style));
	}
      } else {
	$text = $output_pages->createElement('text:p', $element); 
	$item->appendChild($text);
      }

      $list->appendChild($item);

      return $list;
    }

    function createPage($name, $type, $title, $tab_title, $contents, $image = null) {
      global $output_pages, $tempdir;

      $page = $output_pages->createElement('draw:page');
      $page->setAttribute("draw:name",$name);
      $page->setAttribute("draw:style-name","dp1");
      $page->setAttribute("draw:master-page-name",$type);
      $page->setAttribute("presentation:presentation-page-layout-name","AL2T1");
      $page->setAttribute("presentation:use-footer-name","ftr1");
      $page->setAttribute("presentation:use-date-time-name","dtd1");

      //Title
      $page->appendChild(drawPageTitle($title));
      //Tab title
      $page->appendChild(drawTabTitle($tab_title));

      //Image positioning
      if ($image) {
	//Default dimensions and positioning for SVG images
	$x = 0.15;
	$y = 2.5;
	$width = 26.457;
	$height = 15.874;
	if (substr($image, -3) == "png") {
	  $ratio = 0.023291; //(ratio px <=> cm)
	  //Get image dimensions
	  $size = getimagesize($tempdir."/".$image);
	  $width = ($size[0]*$ratio);
	  $height = ($size[1]*$ratio);
	  $y = 3.5;
	  $x = (28-($size[0]*$ratio))/2; //28cm is page width (minus left border) in ODP template, so we center image

	  //If image height is not very big, put somme space around it
	  if ($height <= 7) {
	    $y += ((7-$height)/2);
	    $Yoffset = 7 + 3.5;
	  } else {
	    $Yoffset = $y + $height;
	  }

	  //If image height is too big, position image on the right side
	  if ($height >= 13) {
	    $Yoffset = 5.2;
	    $x = 28 - $width;
	    $Xoffset = $x;
	    $y = 4 + (16 - $height)/2; //20cm is the approx useable height in ODP template, so we center image
	  } else {
	    $Xoffset = 25;
	  }
	}
	$frame = $output_pages->createElement('draw:frame');
	$frame->setAttribute("draw:style-name","gr15");
	$frame->setAttribute("draw:text-style-name","P4");
 	$frame->setAttribute("draw:layer","layout");
 	$frame->setAttribute("svg:width",$width."cm");
 	$frame->setAttribute("svg:height",$height."cm");
 	$frame->setAttribute("svg:x",$x."cm");
 	$frame->setAttribute("svg:y",$y."cm");
	$svg = $output_pages->createElement('draw:image');
 	$svg->setAttribute("xlink:href","Pictures/$image");
 	$svg->setAttribute("xlink:type","simple");
 	$svg->setAttribute("xlink:show","embed");
 	$svg->setAttribute("xlink:actuate","onLoad");
	$frame->appendChild($svg);
	$page->appendChild($frame);
      }

      $frame = $output_pages->createElement('draw:frame');
      $frame->setAttribute("presentation:style-name","pr6");
      $frame->setAttribute("draw:layer","layout");
      $frame->setAttribute("svg:x", "1.5cm");
      $frame->setAttribute("presentation:class","outline");
      $frame->setAttribute("presentation:user-transformed","true");
      //Text outline positioning depending on image positioning
      if ($image) {
	if (substr($image, -3) == "png") {
	  $yc = $Yoffset + 0.4;
	  $heightc = 19 - $yc; //20cm is the approx useable height in ODP template
	  $widthc = $Xoffset;
	} else {
	  $yc = 13;
	  $heightc = 7;
	  $widthc = 25;
	}
      } else {
	$yc = 5;
	$heightc = 15;
	$widthc = 25;
      }
      $frame->setAttribute("svg:width",$widthc."cm");
      $frame->setAttribute("svg:height",$heightc."cm");
      $frame->setAttribute("svg:y", $yc."cm");

      $textbox = $output_pages->createElement('draw:text-box');

      if ($contents) {
	foreach($contents as $element) {
	  $textbox->appendChild(getList($element, "L6"));
	}
      }

      $frame->appendChild($textbox);
      $page->appendChild($frame);

      return $page;
    }

    function createManifest() {
      global $output_manifest, $docs, $lang, $template;

      include("export/$template/settings-odp-$lang.php");

      $manifest = $output_manifest->createElement('manifest:manifest');
      $manifest->setAttribute("xmlns:manifest","urn:oasis:names:tc:opendocument:xmlns:manifest:1.0");
      $manifest->setAttribute("manifest:version","1.2");

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","application/vnd.oasis.opendocument.presentation");
      $file->setAttribute("manifest:version","1.2");
      $file->setAttribute("manifest:full-path","/");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","image/png");
      $file->setAttribute("manifest:full-path","Thumbnails/thumbnail.png");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","text/xml");
      $file->setAttribute("manifest:full-path","settings.xml");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","text/xml");
      $file->setAttribute("manifest:full-path","content.xml");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","text/xml");
      $file->setAttribute("manifest:full-path","meta.xml");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","text/xml");
      $file->setAttribute("manifest:full-path","styles.xml");
      $manifest->appendChild($file);

      foreach($tpl_images as $image) {
	$tmp_array = array_reverse(explode(".", $image));
	$ext = $tmp_array[0];
	if ($ext == "jpg") $mime = "image/jpeg"; else $mime = "image/$ext";
	if ($ext == "wmf") $mime = "";
	$file = $output_manifest->createElement('manifest:file-entry');
	$file->setAttribute("manifest:media-type","$mime");
	$file->setAttribute("manifest:full-path","Pictures/$image");
	$manifest->appendChild($file);
      }

      foreach($docs[0]->getTree() as $section) {
	$name = $section->name;
	$image = $name.".svg";

	$file = $output_manifest->createElement('manifest:file-entry');
	$file->setAttribute("manifest:media-type","image/svg+xml");
	$file->setAttribute("manifest:full-path","Pictures/$image");
	$manifest->appendChild($file);

	$image = "tpl-$name.png";
	$file = $output_manifest->createElement('manifest:file-entry');
	$file->setAttribute("manifest:media-type","image/png");
	$file->setAttribute("manifest:full-path","Pictures/$image");
	$manifest->appendChild($file);

	for ($i = 0; $i < count($docs); $i++) {
	  $image = "$i-$name.png";

	  $file = $output_manifest->createElement('manifest:file-entry');
	  $file->setAttribute("manifest:media-type","image/png");
	  $file->setAttribute("manifest:full-path","Pictures/$image");
	  $manifest->appendChild($file);
	}
      }

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","image/svg+xml");
      $file->setAttribute("manifest:full-path","Pictures/quadrant.svg");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","");
      $file->setAttribute("manifest:full-path","Configurations2/accelerator/current.xml");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","application/vnd.sun.xml.ui.configuration");
      $file->setAttribute("manifest:full-path","Configurations2/");
      $manifest->appendChild($file);

      return $manifest;
    }

    //Finalize Document (on disk)
    $tempdir = $this->temp.uniqid();
    mkdir($tempdir, 0755);
    //$output->save("$tempdir/content.xml");

    copy("export/$template/template_odp.zip", "odf/$odpfile");

    include("export/$template/settings-odp-$lang.php");

    include('libs/pclzip.lib.php');
    $oofile = new PclZip("odf/$odpfile");

    //Predefined XML elements
    $f=fopen("export/$template/odp.xml","r");
    $input = fread($f, filesize("export/$template/odp.xml"));
    fclose($f); 

    //New Pages
    $output_pages = new DOMDocument('1.0', 'UTF-8');

    //Footer
    $presentation_title = $this->docs[0]->getkey("qsosappfamily");
    if ($subtitle) $presentation_title = $subtitle." - ".$presentation_title;

    $footer = $output_pages->createElement('presentation:footer-decl', $presentation_title);
    $footer->setAttribute("presentation:name","ftr1");
    $output_pages->appendChild($footer);

    //First page
    $output_pages->appendChild(createFirstPage($presentation_title, $title));

    //Agenda - First topic
    $output_pages->appendChild(createAgendaPage("Agenda-Template"));

    //Context
    if ($tpl_msg['odp_context_title']) $output_pages->appendChild(createPage("Context", "Green", $tpl_msg['odp_context_title'], $tpl_msg['odp_context_tabtitle'], $tpl_msg['odp_context_content']));

    //Perimeter
    $text = array();
    foreach($names as $name) {
      array_push($text, $name);
    }
    $output_pages->appendChild(createPage("Perimeter", "Green", $tpl_msg['odp_perimeter_title'], $tpl_msg['odp_perimeter_tabtitle'], array($tpl_msg['odp_perimeter_1stphrase'], $text)));

    foreach($docs[0]->getTree() as $section) {
      $name = $section->name;
      $title = $section->title;

      $this->exportTemplateSection($docs[0], $name, $tempdir."/tpl-".$name);
      $image = "tpl-$name.png";

      $v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
      if ($v_list == 0) {
	die("Error 02: ODP generation ".$oofile->errorInfo(true));
      }

      $output_pages->appendChild(createPage("Template-$name", "Green", $tpl_msg['odp_template_title'], $tpl_msg['odp_template_tabtitle'], $docs[0]->getTreeDesc($section->name), $image));
    }

    for ($i=0; $i < count($ids); $i++) {
      //Agenda 
      $output_pages->appendChild(createAgendaPage("Agenda-Solution-$i"));
      //Project description
      $contents = array(
	$docs[$i]->getkey("desc"),
	$docs[$i]->getkey("licensedesc"), 
	$docs[$i]->getkey("url"),
	"TODO",
	$tpl_msg['odp_project_todo']
      );
      $output_pages->appendChild(createPage($names[$i]."-Project", "Blue", $names[$i].$tpl_msg['odp_project_title'], $tpl_msg['odp_project_tabtitle'], $contents));
      foreach($docs[$i]->getTree() as $section) {
	$name = $section->name;
	$title = $section->title;
	$score = $section->score;

	$this->exportSection($docs[$i], $name, $tempdir."/".$i."-".$name);
	$image = "$i-$name.png";

	$v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
	if ($v_list == 0) {
	  die("Error 02: ODP generation ".$oofile->errorInfo(true));
	}

	$output_pages->appendChild(createPage($names[$i]."-$name", "Blue", "$names[$i]", $tpl_msg['odp_project_tabtitle'], $tpl_msg['odp_solution_todo'], $image));
      }
    }

    //Agenda - Third topic
    $output_pages->appendChild(createAgendaPage("Agenda-Synthesis"));

    foreach($docs[0]->getTree() as $section) {
      $name = $section->name;
      $title = $section->title;
      $image = $name.".svg";

      $this->setCriteria($name);
      $this->saveRadar("$tempdir/$image");

      $v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
      if ($v_list == 0) {
	die("Error 02: ODP generation ".$oofile->errorInfo(true));
      }

      $output_pages->appendChild(createPage("Synthesis-$name", "Purple", $tpl_msg['odp_synthesis_title'], $tpl_msg['odp_synthesis_tabtitle'], $tpl_msg['odp_synthesis_todo'], $image));
    }

    $image = "quadrant.svg";
    $this->saveQuadrant("$tempdir/$image");

    $v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 02: ODP generation ".$oofile->errorInfo(true));
    }

    $output_pages->appendChild(createPage("Conclusion", "Purple", $tpl_msg['odp_conclusion_title'], $tpl_msg['odp_conclusion_tabtitle'], $tpl_msg['odp_conclusion_todo'], $image));

    //Recommendations
    if($tpl_msg['odp_agenda_reco']) $output_pages->appendChild(createPage("Reco", "Blue Purple", $tpl_msg['odp_reco_title'], $tpl_msg['odp_reco_tabtitle'], $tpl_msg['odp_reco_content']));

    //License note
    if($tpl_msg['odp_license_title']) $output_pages->appendChild(createPage("Licence", "Licence", $tpl_msg['odp_license_title'], $tpl_msg['odp_license_tabtitle'], $tpl_msg['odp_license_content']));

    //Credits
    if($tpl_msg['odp_credits_title']) {
      $list = array();
      $i = 0;
      foreach($this->ids as $id) {
	$authors = "";
	foreach($docs[$i]->getauthors() as $author) {
	  if($author->name != "") $authors .= $author->name.",";
	}
	if($authors != "") $authors = " (".rtrim($authors, ",").")";
	$tmp_array = array_reverse(explode("/",$files[$i]));
	array_push($list, $names[$i]." : ".$tmp_array[0].$authors);
	$i++;
      }

      $output_pages->appendChild(createPage("Credits", "Licence", $tpl_msg['odp_credits_title'], $tpl_msg['odp_credits_tabtitle'], array($tpl_msg['odp_credits_header'], $list, $tpl_msg['odp_credits_footer'])));
    }

    //hack to remove XML declaration
    foreach($output_pages->childNodes as $node)
	$fragment .= $output_pages->saveXML($node)."\n";

    $content = $input."\n".$fragment."<presentation:settings presentation:mouse-visible=\"false\"/></office:presentation></office:body></office:document-content>";

    $file_content = fopen("$tempdir/content.xml", 'w');
    fwrite($file_content, $content);
    fclose($file_content);

    $v_list = $oofile->add("$tempdir/content.xml", PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 03: ODP generation ".$oofile->errorInfo(true));
    }

    //Manifest generation
    $output_manifest = new DOMDocument('1.0', 'UTF-8');
    $output_manifest->appendChild(createManifest());
    $output_manifest->save("$tempdir/manifest.xml");

    $v_list = $oofile->add("$tempdir/manifest.xml", PCLZIP_OPT_ADD_PATH, "META-INF" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 04: ODP generation ".$oofile->errorInfo(true));
    }

    //Return ODP file to the browser
    header("Location: odf/$odpfile");
    exit;
  }

  //********************************************
  // ODT EXPORT methods
  //********************************************

  function ODT() {
    global $title, $subtitle, $odtfile, $document, $ids, $files, $docs, $names, $output_text, $output_manifest, $output_meta, $tempdir, $msg, $lang, $template;

    $title = $this->title;
    $subtitle = $this->subtitle;
    $ids = $this->ids;
    $files = $this->files;
    $docs = $this->docs;
    $odtfile = "QSOS_".$docs[0]->getkey("qsosappfamily").".odt";
    $names = $this->names;
    $lang = $this->lang;
    $msg = $this->msg;
    $template = $this->template;

    function createTitle($level, $style, $text) {
      global $output_text;

      $title = $output_text->createElement("text:h", $text);
      $title->setAttribute("text:style-name",$style);
      $title->setAttribute("text:outline-level",$level);
      return $title;
    }

    function createFirstTitle1($text) {
      return createTitle("1", "P30", $text);
    }

    function createTitle1($text) {
      return createTitle("1", "Heading_20_1", $text);
    }

    function createTitle2($text) {
      return createTitle("2", "Heading_20_2", $text);
    }

    function createTitle3($text) {
      return createTitle("3", "Heading_20_3", $text);
    }

    function createSimpleP($text) {
      global $output_text;

      $p = $output_text->createElement("text:p", $text);
      $p->setAttribute("text:style-name","Text_20_body");
      return $p;
    }

    function createSimpleTODO($text) {
      global $output_text;

      $p = $output_text->createElement("text:p", $text);
      $p->setAttribute("text:style-name","PHL");
      return $p;
    }

    function createP($contents) {
      global $output_text;

      $p = $output_text->createElement("text:p");
      $p->setAttribute("text:style-name","Text_20_body");
      foreach($contents as $content) {
	switch ($content[0]) {
	  case "t":
	    $span = $output_text->createTextNode($content[1]);
	    break;
	  case "i":
	    $span = $output_text->createElement("text:span", $content[1]);
	    $span->setAttribute("text:style-name","T1");
	    break;
	  case "a":
	    $span = $output_text->createElement("text:a",$content[1]);
	    $span->setAttribute("xlink:type","simple");
	    $span->setAttribute("xlink:href",$content[1]);
	    break;    
	}
	$p->appendChild($span);
      }
      return $p;
    }

    function createList($contents) {
      global $output_text;

      $list = $output_text->createElement("text:list");
      $list->setAttribute("text:continue-numbering","true");
      $list->setAttribute("text:style-name","List_20_1");
      $item = $output_text->createElement("text:list-item");
      if (is_array($contents)) {
	foreach($contents as $subelement) {
	  $item->appendChild(createList($subelement));
	}
      } else {
	$p = $output_text->createElement("text:p", $contents);
	$p->setAttribute("text:style-name","Liste_20_Hiérarchique");
	$item->appendChild($p);
      }
      $list->appendChild($item);

      return $list;
    }

    function createBoldTitle($title) {
      global $output_text;

      $p = $output_text->createElement('text:p', $title);
      $p->setAttribute("text:style-name","P16"); 
      return $p;
    }

    function createImage($image) {
      global $tempdir, $output_text;

      if (substr($image, -3) == "png") {
	$ratio = 0.023291; //(ratio px <=> cm)
	//Get image dimensions
	$size = getimagesize($tempdir."/".$image);
	$width = ($size[0]*$ratio);
	$height = ($size[1]*$ratio);
	$style = "fr7";
      } else {
	//Crop and scale image
	$ratio = 0.65;
	if ($image == "quadrant.svg") {
	  $width = (26.457 - 1)*$ratio;
	  $height = (15.874 - 5)*$ratio;
	  $style = "frQuadrant";
	} else {
	  $width = (26.457 - 1)*$ratio;
	  $height = (15.874 - 6)*$ratio;
	  $style = "frSvg";
	}
      }
    
      $p = $output_text->createElement('text:p');
      $p->setAttribute("text:style-name","P15");
      $frame = $output_text->createElement('draw:frame');
      $frame->setAttribute("draw:style-name",$style);
      $frame->setAttribute("text:anchor-type","as-char");
      $frame->setAttribute("svg:width",$width."cm");
      $frame->setAttribute("svg:height",$height."cm");
      $frame->setAttribute("draw:z-index","191");
      $svg = $output_text->createElement('draw:image');
      $svg->setAttribute("xlink:href","Pictures/$image");
      $svg->setAttribute("xlink:type","simple");
      $svg->setAttribute("xlink:show","embed");
      $svg->setAttribute("xlink:actuate","onLoad");
      $frame->appendChild($svg);
      $p->appendChild($frame);

      return $p;
    }

    function createImageEmbedded($image, $width, $height) {
      global $output_text;
    
      $p = $output_text->createElement('text:p');
      $p->setAttribute("text:style-name","P15");
      $frame = $output_text->createElement('draw:frame');
      $frame->setAttribute("draw:style-name","fr7");
      $frame->setAttribute("text:anchor-type","as-char");
      $frame->setAttribute("svg:width",$width);
      $frame->setAttribute("svg:height",$height);
      $frame->setAttribute("draw:z-index","191");
      $svg = $output_text->createElement('draw:image');
      $svg->setAttribute("xlink:href","Pictures/$image");
      $svg->setAttribute("xlink:type","simple");
      $svg->setAttribute("xlink:show","embed");
      $svg->setAttribute("xlink:actuate","onLoad");
      $frame->appendChild($svg);
      $p->appendChild($frame);

      return $p;
    }

    function createManifest() {
      global $output_manifest, $lang, $docs, $template;

      include("export/$template/settings-odt-$lang.php");

      $manifest = $output_manifest->createElement('manifest:manifest');
      $manifest->setAttribute("xmlns:manifest","urn:oasis:names:tc:opendocument:xmlns:manifest:1.0");
      $manifest->setAttribute("manifest:version","1.2");

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","application/vnd.oasis.opendocument.text");
      $file->setAttribute("manifest:version","1.2");
      $file->setAttribute("manifest:full-path","/");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","image/png");
      $file->setAttribute("manifest:full-path","Thumbnails/thumbnail.png");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","application/binary");
      $file->setAttribute("manifest:full-path","layout-cache");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","text/xml");
      $file->setAttribute("manifest:full-path","settings.xml");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","text/xml");
      $file->setAttribute("manifest:full-path","content.xml");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","text/xml");
      $file->setAttribute("manifest:full-path","meta.xml");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","text/xml");
      $file->setAttribute("manifest:full-path","styles.xml");
      $manifest->appendChild($file);

      foreach($tpl_images as $image) {
	$tmp_array = array_reverse(explode(".", $image));
	$ext = $tmp_array[0];
	if ($ext == "jpg") $mime = "image/jpeg"; else $mime = "image/$ext";
	if ($ext == "wmf") $mime = "";
	$file = $output_manifest->createElement('manifest:file-entry');
	$file->setAttribute("manifest:media-type","$mime");
	$file->setAttribute("manifest:full-path","Pictures/$image");
	$manifest->appendChild($file);
      }

      foreach($docs[0]->getTree() as $section) {
	$name = $section->name;
	$image = $name.".svg";

	$file = $output_manifest->createElement('manifest:file-entry');
	$file->setAttribute("manifest:media-type","image/svg+xml");
	$file->setAttribute("manifest:full-path","Pictures/$image");
	$manifest->appendChild($file);

	$image = "tpl-$name.png";
	$file = $output_manifest->createElement('manifest:file-entry');
	$file->setAttribute("manifest:media-type","image/png");
	$file->setAttribute("manifest:full-path","Pictures/$image");
	$manifest->appendChild($file);

	for ($i = 0; $i < count($docs); $i++) {
	  $image = "$i-$name.png";

	  $file = $output_manifest->createElement('manifest:file-entry');
	  $file->setAttribute("manifest:media-type","image/png");
	  $file->setAttribute("manifest:full-path","Pictures/$image");
	  $manifest->appendChild($file);
	}
      }

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","image/svg+xml");
      $file->setAttribute("manifest:full-path","Pictures/quadrant.svg");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","application/rdf+xml");
      $file->setAttribute("manifest:full-path","manifest.rdf");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","");
      $file->setAttribute("manifest:full-path","Configurations2/accelerator/current.xml");
      $manifest->appendChild($file);

      $file = $output_manifest->createElement('manifest:file-entry');
      $file->setAttribute("manifest:media-type","application/vnd.sun.xml.ui.configuration");
      $file->setAttribute("manifest:full-path","Configurations2/");
      $manifest->appendChild($file);

      return $manifest;
    }

    function createMeta() {
      global $output_meta, $lang, $docs, $title, $template;

      include("export/$template/settings-odt-$lang.php");

      $meta = $output_meta->createElement('office:document-meta');
      $meta->setAttribute("xmlns:office","urn:oasis:names:tc:opendocument:xmlns:office:1.0");
      $meta->setAttribute("xmlns:xlink","http://www.w3.org/1999/xlink");
      $meta->setAttribute("xmlns:dc","http://purl.org/dc/elements/1.1/");
      $meta->setAttribute("xmlns:meta","urn:oasis:names:tc:opendocument:xmlns:meta:1.0");
      $meta->setAttribute("xmlns:ooo","http://openoffice.org/2004/office");
      $meta->setAttribute("xmlns:grddl","http://www.w3.org/2003/g/data-view#");
      $meta->setAttribute("office:version","1.2");
      $ometa = $output_meta->createElement('office:meta');
      $ometa->appendChild($output_meta->createElement("meta:generator","O3S"));
      $ometa->appendChild($output_meta->createElement("dc:title",$docs[0]->getkey("qsosappfamily")));
      $ometa->appendChild($output_meta->createElement("dc:subject",$tpl_subject));
      $ometa->appendChild($output_meta->createElement("meta:creation-date",Date('Y-m-d').'T'.Date('H:i:s')));
      $ometa->appendChild($output_meta->createElement("meta:initial-creator",$tpl_creator));
      $ometa->appendChild($output_meta->createElement("dc:date",Date('Y-m-d').'T'.Date('H:i:s')));
      $ometa->appendChild($output_meta->createElement("dc:creator",$tpl_creator));
      $ometa->appendChild($output_meta->createElement("meta:document-statistic"));
      $mud = $output_meta->createElement('meta:user-defined',$tpl_client1);
      $mud->setAttribute("meta:name","Client 1");
      $ometa->appendChild($mud);
      $mud = $output_meta->createElement('meta:user-defined', $tpl_client2);
      $mud->setAttribute("meta:name","Client 2");
      $ometa->appendChild($mud);
      $mud = $output_meta->createElement('meta:user-defined', Date('d-m-Y'));
      $mud->setAttribute("meta:name","Date");
      $ometa->appendChild($mud);
      $mud = $output_meta->createElement('meta:user-defined', $tpl_projet);
      $mud->setAttribute("meta:name","Projet");
      $ometa->appendChild($mud);
      $mud = $output_meta->createElement('meta:user-defined', "1");
      $mud->setAttribute("meta:name","Version Majeure");
      $ometa->appendChild($mud);
      $mud = $output_meta->createElement('meta:user-defined', "0");
      $mud->setAttribute("meta:name","Version Mineure");
      $ometa->appendChild($mud);
      $meta->appendChild($ometa);

      return $meta;
    }

    function createContent($contents) {
      global $output_text;

      if (!$contents) return;

      foreach($contents as $content) {
	switch ($content[0]) {
	    case "firsttitle1":
		$output_text->appendChild(createFirstTitle1($content[1]));
		break;
	    case "title1":
		$output_text->appendChild(createTitle1($content[1]));
		break;
	    case "title2":
		$output_text->appendChild(createTitle2($content[1]));
		break;
	    case "title3":
		$output_text->appendChild(createTitle3($content[1]));
		break;
	    case "todo":
		$output_text->appendChild(createSimpleTODO($content[1]));
		break;
	    case "simplep":
		$output_text->appendChild(createSimpleP($content[1]));
		break;
	    case "p":
		$output_text->appendChild(createP($content[1]));
		break;
	    case "list":
		$output_text->appendChild(createList($content[1]));
		break;
	    case "image":
		$output_text->appendChild(createImageEmbedded($content[1],$content[2],$content[3]));
		break;
	}
      }
    }

    //Finalize Document (on disk)
    $tempdir = $this->temp.uniqid();
    mkdir($tempdir, 0755);
    //$output->save("$tempdir/content.xml");

    copy("export/$template/template_odt.zip", "odf/$odtfile");

    include('libs/pclzip.lib.php');
    $oofile = new PclZip("odf/$odtfile");

    //Predefined XML elements
    $f=fopen("export/$template/odt.xml","r");
    $input = fread($f, filesize("export/$template/odt.xml"));
    fclose($f); 

    //Contents
    include("export/$template/settings-odt-$lang.php");
    $output_text = new DOMDocument('1.0', 'UTF-8');

    //1. Introduction
    createContent($tpl_msg_content['Introduction']);

    //2. Template
    createContent($tpl_msg_content['Template']);

    $list = array(); //List of sections descriptions
    $section_title = array(); //List of sections titles
    $section_name = array(); //List of sections names
    $images = array(); //List of sections images (MindMap)
    foreach($docs[0]->getTree() as $section) {
      $name = $section->name;
      $title = $section->title;
      $image = "tpl-".$name;

      $this->exportTemplateSection($docs[0], $name, "$tempdir/$image");
      $image = "$image.png";
      $desc = $docs[0]->getTreeDesc($section->name);

      array_push($list, $desc);
      array_push($section_title, $title);
      array_push($section_name, $name);

      $v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
      if ($v_list == 0) {
	die("Error 02: ODT generation ".$oofile->errorInfo(true));
      }

      array_push($images, createImage($image));
    }

    //List of sections
    $output_text->appendChild(createList($docs[0]->getTreeDesc()));

    //For each section display title, image and description
    $i = 0;
    foreach($images as $image) {
      //$output_text->appendChild(createBoldTitle($section_title[$i]));
      $output_text->appendChild(createTitle2($section_title[$i]));
      $output_text->appendChild($image);
      $output_text->appendChild(createList($list[$i]));
      $i++;
    }

    //4. Solutions
    createContent($tpl_msg_content['Solutions_header']);

    $contents = array();
    for ($i=0; $i < count($ids); $i++) {
      array_push($contents, $names[$i]." (".$docs[$i]->getkey("url").") : ".$docs[$i]->getkey("desc"));
    }
    $output_text->appendChild(createList($contents));
    createContent($tpl_msg_content['Solutions_header_todo']);

    for ($i=0; $i < count($ids); $i++) {
      $output_text->appendChild(createTitle2($names[$i]));
      createContent($tpl_msg_content['Solutions_header_project']);

      $this->exportSection($docs[$i], $section_name[0], $tempdir."/".$i."-".$section_name[0]);
      $image = $i."-".$section_name[0].".png";

      $v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
      if ($v_list == 0) {
	die("Error 02: ODP generation ".$oofile->errorInfo(true));
      }   
      $output_text->appendChild(createImage($image));
      createContent($tpl_msg_content['Solutions_footer_project']);

      createContent($tpl_msg_content['Solutions_header_coverage']);
      for ($k=1; $k < count($section_title); $k++) {
	$output_text->appendChild(createBoldTitle($section_title[$k]));

	$this->exportSection($docs[$i], $section_name[$k], $tempdir."/".$i."-".$section_name[$k]);
	$image = $i."-".$section_name[$k].".png";

	$v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
	if ($v_list == 0) {
	  die("Error 02: ODP generation ".$oofile->errorInfo(true));
	}   
	$output_text->appendChild(createImage($image));
	createContent($tpl_msg_content['Solutions_footer_coverage']);
      }
    }

    //5. Synthesis
    createContent($tpl_msg_content['Analysis_header_comparison']);

    for($i=0; $i < count($section_name); $i++) {
      $image = $section_name[$i].".svg";

      $this->setCriteria($section_name[$i]);
      $this->saveRadar("$tempdir/$image");

      $v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
      if ($v_list == 0) {
	die("Error 02: ODT generation ".$oofile->errorInfo(true));
      }
      $output_text->appendChild(createBoldTitle($section_title[$i]));
      $output_text->appendChild(createImage($image));
      createContent($tpl_msg_content['Analysis_footer_comparison']);
    }

    createContent($tpl_msg_content['Analysis_header_conclusion']);
    $image = "quadrant.svg";
    $this->saveQuadrant("$tempdir/$image");

    $v_list = $oofile->add("$tempdir/$image", PCLZIP_OPT_ADD_PATH, "Pictures" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 02: ODT generation ".$oofile->errorInfo(true));
    }
    $output_text->appendChild(createImage($image));
    createContent($tpl_msg_content['Analysis_footer_conclusion']);

    //6. Appendixes
    //QSOS
    createContent($tpl_msg_content['Appendixes_header']);

    //Sources or Credits
    createContent($tpl_msg_content['Sources']);
    createContent($tpl_msg_content['Credits']);

    $list = array();
    $i = 0;
    foreach($this->ids as $id) {
      $authors = "";
      if($tpl_msg_content['Credits']) {
	foreach($docs[$i]->getauthors() as $author) {
	  if($author->name != "") $authors .= $author->name.",";
	}
	if($authors != "") $authors = " (".rtrim($authors, ",").")";
      }
	$tmp_array = array_reverse(explode("/",$files[$i]));
      array_push($list, $names[$i]." : ".$tmp_array[0].$authors);
      $i++;
    }
    $output_text->appendChild(createList($list));

    //hack to remove XML declaration
    foreach($output_text->childNodes as $node)
	$fragment .= $output_text->saveXML($node)."\n";

    $content = $input."\n".$fragment."</office:text></office:body></office:document-content>";

    $file_content = fopen("$tempdir/content.xml", 'w');
    fwrite($file_content, $content);
    fclose($file_content);

    $v_list = $oofile->add("$tempdir/content.xml", PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 03: ODT generation ".$oofile->errorInfo(true));
    }

    //Manifest generation
    $output_manifest = new DOMDocument('1.0', 'UTF-8');
    $output_manifest->appendChild(createManifest());
    $output_manifest->save("$tempdir/manifest.xml");

    $v_list = $oofile->add("$tempdir/manifest.xml", PCLZIP_OPT_ADD_PATH, "META-INF" ,PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 04: ODT generation ".$oofile->errorInfo(true));
    }

    //Meta generation
    $output_meta = new DOMDocument('1.0', 'UTF-8');
    $output_meta->appendChild(createMeta());
    $output_meta->save("$tempdir/meta.xml");

    $v_list = $oofile->add("$tempdir/meta.xml", PCLZIP_OPT_REMOVE_PATH, $tempdir);
    if ($v_list == 0) {
      die("Error 05: ODT generation ".$oofile->errorInfo(true));
    }

    //Return ODT file to the browser
    header("Location: odf/$odtfile");
    exit;
  }
}

?>
