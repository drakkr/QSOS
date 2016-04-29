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
**  O3S Formats classes
**  QSOSDocument.php: PHP classes to access and manipulate QSOS documents
**
*/

//Class representing a QSOS criterion (<section/> or <element/>)
class QSOSCriterion {
	var $name;
	var $title;
	var $children;
	var $score;
}

//Class representing a QSOS author (<author/>)
class Author {
	var $name;
	var $email;
}

//Class representing a QSOS document
class QSOSDocument {
	var $doc;
	var $xpath;

    //$file: filename (or URI) of the QSOS document to load
	public function __construct($file) {
		//if (file_exists($file) || (strpos(current(get_headers($file)), "OK"))) {
		if (file_exists($file)) {
			$this->doc = new DOMDocument();
			$this->doc->load($file);
			$this->xpath = new DOMXPath($this->doc);
		} else {
			return 'Failed to open file ' . $file . '(Hint: try to fix webserver user permissions)';
		}
	}

    //$name: name of the tested element
    //Returns: true if element has children elements
	public function hassubelements($name) {
		$query = "//*[@name='".$name."']/element";
		$nb = $this->xpath->query($query);
		return $nb->length;
	}

    //$element: name of the XML header tag
    //Returns: the value of a header tag (like appname, release, ...)
	public function getkey($element) {
		$nodes = $this->xpath->query("//".$element);
		if ($nodes->length != 0) {
			return $nodes->item(0)->nodeValue;
		} else {
			return "";
		}
	}

    //$name: name of the section to return
    //Returns: section in XML format
	public function getsection($name) {
		$nodes = $this->xpath->query("//section[@name='".$name."']");
		if ($nodes->length != 0) {
			return $nodes->item(0);
		} else {
			return "";
		}
	}
	
    //$element: name of the element
    //$subelement: name of the XML tag
    //Returns: value of the XML tag included in the element
	public function getgeneric($element, $subelement) {
		$nodes = $this->xpath->query("//*[@name='".$element."']/".$subelement);
		if ($nodes->length != 0) {
			return $nodes->item(0)->nodeValue;
		} else {
			return "";
		}
	}

    //$element: name of the element
    //Returns: value of the <score/> tag included in the element
	public function getkeyscore($element) {
		$nodes = $this->xpath->query("//*[@name='".$element."']/score");
		if ($nodes->length != 0) {
			return $nodes->item(0)->nodeValue;
		} else {
			return -1;
		}
	}

    //$element: name of the element (<section/> or <element/>)
    //Returns: value of the "title" attribute of the element
	public function getkeytitle($element) {
		$nodes = $this->xpath->query("//*[@name='".$element."']");
		if ($nodes->length != 0) {
			return $nodes->item(0)->getAttribute('title');
		} else {
			return "";
		}
	}

	//Returns: template's file name as it should be: domain-version_language.mm
	public function getFileName() {
	  $name =  $this->getkey('appname')."-".$this->getkey('release')."_".$this->getkey('language').".qsos";
	  return str_replace(' ', '-', $name);
	}


    //Returns: array of Author objects (cf. Author class above)
	public function getauthors() {
		$authors = array();	

		$nodes = $this->xpath->query("//author");
		for ($i=0; $i < $nodes->length; $i++) {
			$author = new Author();

			$names = $nodes->item($i)->getElementsByTagName("name");
			if ($names->length > 0) {
				$author->name = $names->item(0)->textContent;
			} else {
				$author->name = "";
			}

			$titles = $nodes->item($i)->getElementsByTagName("email");
			if ($titles->length > 0) {
				$author->email = $titles->item(0)->textContent;
			} else {
				$author->email = "";
			}
			array_push($authors, $author);
		}

		return $authors;
	}

    //Returns the name of a criterion's parent
	function getParent($name) {
		$nodes = $this->xpath->query("//*[@name='".$name."']");
		if ($nodes->length > 0) {
			return $nodes->item(0)->parentNode;
		}
		else {
			return null;
		}
	}

    //Returns: tree of QSOSCriterion objects representing the scored criteria of the QSOS document
	public function getTree() {
		$tree = array();
		$sections = $this->xpath->query("//section");
		foreach ($sections as $section) {
			$criterion = new QSOSCriterion();
			$criterion->name = $section->getAttribute('name');
			$criterion->title = $section->getAttribute('title');
			$criterion->children = $this->getSubTree($criterion->name);
			$criterion->score = $this->renderScore($criterion->children);
			array_push($tree, $criterion);
		}
		return $tree;
	}

    //Recursive function
    //$name: name of the element
    //Returns: tree of QSOSCriterion objects representing the scored criteria of the element
	public function getSubTree($name) {
		$tree = array();
		$elements = $this->xpath->query("//*[@name='".$name."']/element");
		foreach ($elements as $element) {
			$criterion = new QSOSCriterion();
			$criterion->name = $element->getAttribute('name');
			$criterion->title = $element->getAttribute('title');

			if ($this->hassubelements($criterion->name)) {
				$criterion->children = $this->getSubTree($criterion->name);
				$criterion->score = $this->renderScore($criterion->children);
				array_push($tree, $criterion);
			} else {
				$criterion->children = null;
				$criterion->score = $this->getkeyscore($criterion->name);
				if ($criterion->score == "") $criterion->score = null;
				if ($criterion->score != -1) array_push($tree, $criterion);
			}
		}
		return $tree;
	}

    //Recursive function
    //$name: name of the element
    //Returns: tree of criteria descriptions of a given section representing the scored criteria of the element
	public function getTreeDesc($name = null) {
		$tree = array();
		if ($name) {
		  $elements = $this->xpath->query("//*[@name='".$name."']/element");
		} else {
		  $elements = $this->xpath->query("//section");
		}
		foreach ($elements as $element) {
			$subname = $element->getAttribute('name');
			$desc = $this->getgeneric($subname, "desc");
			$entry =  $element->getAttribute('title')." : ".$this->getgeneric($subname, "desc");
			array_push($tree, $entry);
		}
		return $tree;
	}

    //Returns: tree of QSOSCriterion objects representing the scored criteria of the QSOS document
	public function getWeightedTree($weights) {
		$tree = array();
		$sections = $this->xpath->query("//section");
		foreach ($sections as $section) {
			$criterion = new QSOSCriterion();
			$criterion->name = $section->getAttribute('name');
			$criterion->title = $section->getAttribute('title');
			$criterion->children = $this->getWeightedSubTree($criterion->name, $weights);
			$criterion->score = $this->renderWeightedScore($criterion->children, $weights);
			array_push($tree, $criterion);
		}
		return $tree;
	}

    //Recursive function
    //$name: name of the element
    //Returns: tree of QSOSCriterion objects representing the scored criteria of the element
	public function getWeightedSubTree($name, $weights) {
		$tree = array();
		$elements = $this->xpath->query("//*[@name='".$name."']/element");
		foreach ($elements as $element) {
			$criterion = new QSOSCriterion();
			$criterion->name = $element->getAttribute('name');
			$criterion->title = $element->getAttribute('title');

			if ($this->hassubelements($criterion->name)) {
				$criterion->children = $this->getWeightedSubTree($criterion->name, $weights);
				$criterion->score = $this->renderWeightedScore($criterion->children, $weights);
				array_push($tree, $criterion);
			} else {
				$criterion->children = null;
				$criterion->score = $this->getkeyscore($criterion->name);
				if ($criterion->score == "") $criterion->score = null;
				if ($criterion->score != -1) array_push($tree, $criterion);
			}
		}
		return $tree;
	}

    //$tree: tree of QSOSCriterion objects to render
    //Returns: the rendered score of the single QSOScriterion in $tree
    //Recursive function
	public function renderScore($tree) {
		$score = 0;
		$sum = 0;
		$totalWeight = 0;

		//[FIXME] desc element with only desc subelement(s) shoul be properly managed
		if (count($tree) == 0) return "NA";

		for ($i=0; $i < count($tree); $i++) {
			$totalWeight++;
			if ($tree[$i]->score == null) {
				$isRenderable = false;
			}
			$sum += round($tree[$i]->score, 2);
		}

		$score = round(($sum/$totalWeight), 2);
		
		return $score;
	}

    //$tree: tree of QSOSCriterion objects to render
    //Returns: the rendered score of the single QSOScriterion in $tree
    //Recursive function
	public function renderWeightedScore($tree, $weights) {
		$score = 0;
		$sum = 0;
		$totalWeight = 0;

		//[FIXME] desc element with only desc subelement(s) shoul be properly manage
		if (count($tree) == 0) return "NA";

		for ($i=0; $i < count($tree); $i++) {
			$name = $tree[$i]->name;
			$weight = $weights[$name];
			if (!isset($weight)) $weight = 1;
			$totalWeight = $totalWeight + $weight;
			if ($tree[$i]->score == null) {
				$isRenderable = false;
			}
			$sum += round(($tree[$i]->score)*$weight, 2);
		}

		if ($totalWeight == 0) return 0;
    $score = round(($sum/$totalWeight), 2);
		
		return $score;
	}

        //$element: name of the XML element to count
        //Returns: number of XML element occurences
	public function getcountkey($element) {
		return $this->xpath->evaluate("count(//$element)");
	}

        //$element: name of the XML element to count
        //Returns: number of XML element occurences
	public function getdeep() {
		return $this->xpath->evaluate("count(element/element)");
	}

	private function libxml_error($error)  {
	  $return = "";
	  switch ($error->level) {
	      case LIBXML_ERR_WARNING:
		  $return .= "Warning $error->code: ";
		  break;
	      case LIBXML_ERR_ERROR:
		  $return .= "Error $error->code: ";
		  break;
	      case LIBXML_ERR_FATAL:
		  $return .= "Fatal Error $error->code: ";
		  break;
	  }
	  $return .= trim($error->message);
	  $return .= " on line $error->line";

	  return $return;
	}

	private function libxml_errors() {
	  $return = array();
	  $errors = libxml_get_errors();
	  foreach ($errors as $error) {
	      array_push($return, $this->libxml_error($error));
	  }
	  libxml_clear_errors();

	  return $return;
	}   

	//Check if template is a valid QSOS document
	//Returns: ok if XSD schema validation passed, if not: array of errors
	public function checkXSD($schema) {
	  libxml_use_internal_errors(true);

	  if (!$this->doc->schemaValidate($schema)) {
	    return $this->libxml_errors();
	  } else return "ok";
	}

}
?>
