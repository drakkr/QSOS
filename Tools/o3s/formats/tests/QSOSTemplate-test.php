<?php
/* Tests of the QSOSTemplate class */

include('../libs/QSOSTemplate.php');

$t = new QSOSTemplate("test-template.mm");

function out($text) {
  echo $text."<br/><br/>";
}

out("getNodeExists('maturity') : ".$t->getNodeExists('maturity'));

out("getNodeExists('potnawak') : ".$t->getNodeExists('potnawak'));

out("getNode('maturity') : ".print_r($t->getNode('maturity')));

out("getNodeAttribute('maturity','potnawak') : ".$t->getNodeAttribute('maturity','potnawak'));

out("getNodeValue('potnawak') : ".$t->getNodeValue('potnawak'));

out("getNodeValue('maturity') : ".$t->getNodeValue('maturity'));

out("getNodeCreationDate('maturity')) : ".$t->getNodeCreationDate('maturity'));

out("getNodeModificationDate('version_entry')) : ".$t->getNodeModificationDate('version_entry'));

out("getTemplateCreationDate() : ".$t->getTemplateCreationDate());

out("getTemplateModificationDate() : ".$t->getTemplateModificationDate());

out("getTemplateDomainName() : ".$t->getTemplateDomainName());

out("getTemplateFileName() : ".$t->getTemplateFileName());

out("getAuthors() : ".print_r($t->getAuthors()));

out("checkTemplateMetadata() : ".print_r($t->checkTemplateMetadata()));

out("checkFreemindXSD() : ".print_r($t->checkFreemindXSD('../xml/xsd/freemind.xsd')));

?>