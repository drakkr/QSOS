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
**  QSOSTemplates.php: PHP classes to access and manipulate QSOS templates
**
*/

//Class representing a QSOS author (<author/>)
class Author {
  var $name;
  var $email;
}

//Class representing a QSOS template (Freemind mm format)
class QSOSTemplate {
  var $doc;
  var $xpath;

  //$file: filename (or URI) of the QSOS template to load
  function __construct($file) {
    if (file_exists($file)) {
      $this->doc = new DOMDocument();
      $this->doc->load($file);
      $this->xpath = new DOMXPath($this->doc);
    } else {
      return 'Failed to open file '.$file;
    }
  }

  //$id: ID of node to check
  //Returns: true if node exists, false if not
  public function getNodeExists($id) {
    $nodes = $this->xpath->query("//*[@ID='".$id."']");
    if ($nodes->length != 0) return true; else return false;
  }

  //$id: Node Id
  //Returns: XML node or false if it doesn't exist
  public function getNode($id) {
    $nodes = $this->xpath->query("//*[@ID='".$id."']");
    if ($nodes->length != 0) return $nodes->item(0); else return false;
  }

  //$id: Node Id
  //$attribute: name of the attribute to get
  //Returns: value of node's attribute or false if it doesn't exist
  public function getNodeAttribute($id, $attribute) {
    $nodes = $this->xpath->query("//*[@ID='".$id."']");
    if ($nodes->length != 0) {
      if($nodes->item(0)->getAttribute($attribute)) return $nodes->item(0)->getAttribute($attribute); else return false;
    } else {
      return false;
    }
  }

  //$id: Node Id
  //Returns: value of node's TEXT attribute or false if it doesn't exist
  public function getNodeValue($id) {
    return $this->getNodeAttribute($id, "TEXT");
  }

  //$id: Node Id
  //Returns: value of node's creation date in YYYY-MM-DD format or false if it doesn't exist
  public function getNodeCreationDate($id) {
    if($timestamp =  $this->getNodeAttribute($id, "CREATED")) {
      $date = $timestamp/1000;
      return date('Y-m-d', $date);
    } else return false;
  }

  //$id: Node Id
  //Returns: value of node's modification date in YYYY-MM-DD format or false if it doesn't exist
  public function getNodeModificationDate($id) {
    if($timestamp =  $this->getNodeAttribute($id, "MODIFIED")) {
      $date = $timestamp/1000;
      return date('Y-m-d', $date);
    } else return false;
  }

  //Returns: value of template's modification date in YYYY-MM-DD format or false if it doesn't exist
  public function getTemplateModificationDate() {
    if($date = $this->getNodeValue('update_entry')) {
      return $date; 
    } else { //If information doesn't exist we calculate it (max of node's modification time)
      if($result = $this->xpath->evaluate("//node/@MODIFIED")) {
	$highest = '';
	foreach ( $result as $node ) {
	  if ( $node->textContent > $highest ) {
	    $highest = $node->textContent;
	  }
	}
	$date = $highest/1000;
	return date('Y-m-d', $date);;
      } else return false;
    }
  }

  //Returns: value of template's creation date in YYYY-MM-DD format or false if it doesn't exist
  public function getTemplateCreationDate() {
    if($date = $this->getNodeValue('creation_entry')) {
      return $date; 
    } else { //If information doesn't exist we calculate it (max of node's creation time)
      if($result = $this->xpath->evaluate("//node/@CREATED")) {
	$highest = '';
	foreach ( $result as $node ) {
	  if ( $node->textContent > $highest ) {
	    $highest = $node->textContent;
	  }
	}
	$date = $highest/1000;
	return date('Y-m-d', $date);;
      } else return false;
    }
  }

  //Returns: template's domaine name
  public function getTemplateDomainName() {
    return $this->getNodeValue('type');
  }

  //Returns: template's version
  public function getTemplateVersion() {
    return $this->getNodeValue('version_entry');
  }

  //Returns: template's version
  public function getTemplateLanguage() {
    return $this->getNodeValue('language_entry');
  }

  //Returns: template's file name as it should be: domain-version_language.mm
  public function getTemplateFileName() {
    $name =  $this->getNodeValue('type')."-".$this->getNodeValue('version_entry')."_".$this->getNodeValue('language_entry').".mm";
    return str_replace(' ', '-', $name);
  }

  //Returns: array of Author type
  public function getAuthors() {
    $authors = array();	
    $nodes = $this->xpath->query("//node[@ID='authors']/node[@TEXT='author']");

    for ($i=0; $i < $nodes->length; $i++) {
      $author = new Author();
      $authorId = $nodes->item($i)->getAttribute('ID');

      $author->name =  $this->xpath->query("//node[@ID='$authorId']/node[@TEXT='name']/node")->item(0)->getAttribute('TEXT');
      $author->email =  $this->xpath->query("//node[@ID='$authorId']/node[@TEXT='email']/node")->item(0)->getAttribute('TEXT');

      array_push($authors, $author);
    }

    return $authors;
  }

  //Check if template's metadata is valid
  //Returns: ok if usefull metadata is present, if not: array of missing fields
  public function checkTemplateMetadata() {
    $fields = array("version_entry","language_entry", "authors", "creation_entry", "update_entry");
    $errors = array();
    foreach($fields as $field) {
	$tmp_array = explode('_', $field);
      if(!($this->getNodeValue($field))) array_push($errors, $tmp_array[0]);
    }

    if(count($errors) > 0) return $errors; else return "ok";
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

  //Check if template is a valid Freemind document
  //Returns: ok if Freemind schema validation passed, if not: array of errors
  //Please note that Freemind's schema has been modified to pass on 0.9 maps
  public function checkFreemindXSD($schema) {
    libxml_use_internal_errors(true);

    if (!$this->doc->schemaValidate($schema)) {
      return $this->libxml_errors();
    } else return "ok";
  }


}
?>
