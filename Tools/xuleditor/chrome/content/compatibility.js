/**
 *  Copyright (C) 2006-2013 Atos
 *
 **  Authors: Raphael Semeteys <raphael.semeteys@atos.net>
 **           Timothée Ravier <timothee.romain.ravier@gmail.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *
 *  QSOS XUL Editor
 *  compability.js: functions to import/export/update evaluations thanks to xslt sheet
 *
**/

//Create a new qsos document from a local Fremind template (.mm)
function newFileFromLocalTemplate() {
  try {
    // Asks for the template
    var filename = pickAFile(".mm", strbundle.getString("FreeMindTemplate"));
    if (filename == "") {
      return false;
    }
    var xml = loadFile(filename);
    var lang = xml.evaluate("//*[@ID='language_entry']", xml, null, XPathResult.ANY_TYPE,null).iterateNext().getAttribute("TEXT");

    if (xml == null) {
      return false;
    }   
  } catch (e) {
    alert("newFileFromLocalTemplate: " + e.message);
  }
  return newFileFromTemplate(xml, lang);
}  

//Create a new qsos document from a Fremind template (.mm) by applying a XSL transformation
//Input: xml: the mm template to be transformed
function newFileFromTemplate(xml, lang) {  
  try {
    // Uses "included" xslt to convert the template to an evaluation
    var xslt;    
    switch(lang) {
      case "fr":
	xslt = parseXML(template_to_qsos_2_0_fr);
	break;
      case "en":
	xslt = parseXML(template_to_qsos_2_0_en);
	break;
      default:
	alert("Language "+lang+" is not supported, reverting to English");
	xslt = parseXML(template_to_qsos_2_0_en);
    }    
    var error = xslt.getElementsByTagName("parsererror");
    if (error.length == 1) {
      alert("An error occurred while parsing the XSLT! This is a bug. Please report it using this description:\nThe Freemind to QSOS 2.0 XSLT doesn't work.\nPlease include your evaluations in the report.");
      alert("newFileFromTemplate: " + strbundle.getString("parsingError") + "\n\n" + error[0].textContent);
      return false;
    }

    // FIXME find a way to open the right XSLT from the extension
    /*xslt = loadXSLT("chrome://qsos-xuled/content/template_to_qsos_2_0.xsl");
    if (xslt == null) {
      return false;
    }*/

    myDoc = new Document();

    var processor = new XSLTProcessor();
    processor.importStylesheet(xslt);
    myDoc.setSheet(processor.transformToDocument(xml));

    try {
      setupEditorForEval();
    } catch (e) {
      alert("newFileFromTemplate: an error occured while setting up the editor: " + e.message);
      closeFile();
      return false;
    }
  } catch (e) {
    alert("newFileFromTemplate: " + e.message);
  }
}

function updateFromLocalTemplate() {
    try{
      if (myDoc == null) {
	alert("updateFromTemplate: You need to open an evaluation before trying to update it!");
	return false;
      }

      // Open template file dialog to choose a template to take stuff from
      var filename = pickAFile(".mm", strbundle.getString("FreeMindTemplate"));
      if (filename == "") {
	return false;
      }
      var template = loadFile(filename);
      if (template == null) {
	return false;
      }
      var lang = template.evaluate("//*[@ID='language_entry']", template, null, XPathResult.ANY_TYPE,null).iterateNext().getAttribute("TEXT");
    } catch(e) {
      alert("updateFromLocalTemplate: Problem in file loading or processing: " + e.message);
  }
  return updateFromTemplate(template, lang);

}  

function updateFromTemplate(template, lang) {
  try{
    var xslt; 
    
    switch(lang) {
      case "fr":
	xslt = parseXML(template_to_qsos_2_0_fr);
	break;
      case "en":
	xslt = parseXML(template_to_qsos_2_0_en);
	break;
      default:
	alert("Language "+lang+" is not supported, reverting to English");
	xslt = parseXML(template_to_qsos_2_0_en);
    } 
    
    var error = xslt.getElementsByTagName("parsererror");
    if (error.length == 1) {
      alert("An error occurred while parsing the XSLT! This is a bug. Please report it using this description:\nThe Freemind to QSOS 2.0 XSLT doesn't work.\nPlease include your evaluations in the report.");
      alert("loadFile: " + strbundle.getString("parsingError") + "\n\n" + error[0].textContent);
      return false;
    }

    var processor = new XSLTProcessor();
    processor.importStylesheet(xslt);
    var templateXML = processor.transformToDocument(template);
  } catch(e) {
    alert("updateFromTemplate: Problem in file loading or processing: " + e.message);
  }

  // Type, version and language testing
  try {
    //Type
    var currentType = myDoc.getqsosappfamily();
    var node = templateXML.evaluate("//header/qsosappfamily", templateXML, null, XPathResult.ANY_TYPE, null).iterateNext();
    if (node) {
      var newType = node.textContent;
    } else {
      alert(strbundle.getString("noTemplateType"));
      return false;
    }

    if (currentType != newType) {
      alert(strbundle.getString("wrongTemplateType") + " " + currentType + " != " + newType);
      return false;
    } 
    
    //Language
    var currentlang = myDoc.getlanguage();
    if (currentlang != lang) {
      if(confirm(strbundle.getString("confirmLangUpdate") + " " + currentlang + " -> " + lang + ".") == false) {
	return false;
      }
    }
    
    //Version
    var currentVersion = myDoc.getqsosspecificformat();
    var node = templateXML.evaluate("//header/qsosspecificformat", templateXML, null, XPathResult.ANY_TYPE, null).iterateNext();
    if (node) {
      var newVersion = node.textContent;
    } else {
      alert(strbundle.getString("noVersion"));
      return false;
    }

    if (newVersion != currentVersion) {
      if(confirm(strbundle.getString("confirmUpdate") + " " + currentVersion + " -> " + newVersion + ".") == false) {
	  return false;
      }
    }
  } catch(e) {
    alert("updateFromTemplate: Problem in info testing stuff: " + e.message);
  }

  // Do the update
  try {
    // Creates of copy in order to work on the sheet easily
    var tmpTemplateXML = parseXML(serializeXML(myDoc.getSheet()));

    // Merge the sections
    var newSheet = mergeSections(myDoc.getSheet(), templateXML, tmpTemplateXML);
    
    // Update the template version
    myDoc.setqsosspecificformat(newVersion);
  } catch(e) {
    alert("updateFromTemplate: merge failed: " + e.message);
  }

  //Update the editor for the new template
  myDoc.setSheet(newSheet);

  // Updates the template type and verison
  var version = (myDoc.getqsosspecificformat() ? "Version "+myDoc.getqsosspecificformat() : "No version");
  document.getElementById("f-sotwarefamily").value = myDoc.getqsosappfamily() + " (" + version +")";

  // Resets the criteria tab
  document.getElementById("f-c-desc").value = "";
  document.getElementById("f-c-desc0").label = strbundle.getString("score0Label");
  document.getElementById("f-c-desc1").label = strbundle.getString("score1Label");
  document.getElementById("f-c-desc2").label = strbundle.getString("score2Label");
  document.getElementById("f-c-score").selectedIndex = -1;
  document.getElementById("f-c-comments").value = "";

  var tree = document.getElementById("criteriaTree");
  var treechildren = document.getElementById("myTreechildren");
  tree.removeChild(treechildren);
  clearChart();
  clearLabels();

  try {
    var tree = document.getElementById("criteriaTree");
    var treechildren = buildtree();
    tree.appendChild(treechildren);
  } catch (e) {
    alert("updateFromTemplate: can't populate the criteria tree: " + e.message);
    return false;
  }

  docHasChanged();
  
  alert(strbundle.getString("successUpdate"));

  return true;
}


function mergeSections(oldSheet, newSheet, tmpOldSheet) {
  try {
    // Removes the old sections form the oldSheet
    oldDocument = oldSheet.evaluate("//document", oldSheet, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null).iterateNext();
    var oldSections = oldSheet.evaluate("//document/section", oldSheet, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
    var section = oldSections.iterateNext();
    while (section != null) {
      oldDocument.removeChild(section);
      oldSections = oldSheet.evaluate("//document/section", oldSheet, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
      section = oldSections.iterateNext();
    }

    // Adds the new ones from the newSheet to the oldSheet, and try to update them if they were previously filled in oldSheet
    var oldDocument = oldSheet.evaluate("//document", oldSheet, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null).iterateNext();
    var newSections = newSheet.evaluate("//document/section", newSheet, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
    var section = newSections.iterateNext();
    while (section != null) {
      oldDocument.appendChild(updateNode(section, tmpOldSheet));
      newSections = newSheet.evaluate("//document/section", newSheet, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
      section = newSections.iterateNext();
    }
  } catch (e) {
    alert("mergeSections: " + e.message);
  }

  return oldSheet;
}


// Updates a section with content from oldSheet
function updateNode(section, oldSheet) {
  try {
    var comments = section.getElementsByTagName("comment");
    var len = comments.length;
    for (var i = 0; i < len; ++i) {
      var id = comments[i].parentNode.getAttribute("name");
      var node = oldSheet.evaluate("//element[@name='" + id + "']", oldSheet, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null).iterateNext();
      if (node) {
        comments[i].textContent = node.getElementsByTagName("comment")[0].textContent;
        comments[i].nextSibling.textContent = node.getElementsByTagName("score")[0].textContent;
      }
    }
  } catch (e) {
    alert("updateNode: " + e.message);
  }

  return section;
}


function exportToFreeMindTemplate() {
  try {
    var xslt = parseXML(qsos_2_0_to_freemind_template);
    var error = xslt.getElementsByTagName("parsererror");
    if (error.length == 1) {
      alert("An error occurred while parsing the XSLT! This is a bug. Please report it using this description:\nThe QSOS 2.0 to Freemind template XSLT doesn't work.\nPlease include your evaluations and this message in the report:\n" + "loadFile: " + strbundle.getString("parsingError") + "\n\n" + error[0].textContent);
      return false;
    }

    var toTrans = myDoc.getSheet();

    var processor = new XSLTProcessor();
    processor.importStylesheet(xslt);
    var tmp = processor.transformToDocument(toTrans);
    var element = tmp.getElementsByTagName("map")[0];

    getPrivilege();

    try { var type = myDoc.get("qsosMetadata/template/type"); } catch (e) { var type = ""; }
    try { var language = myDoc.get("qsosMetadata/language"); } catch (e) { var language = ""; }
    var suggest = type; // + "_" + version;
    if ((language != "en") && (language != "EN")) {
      suggest += "_" + language;
    }

    var nsIFilePicker = Components.interfaces.nsIFilePicker;
    var fp = Components.classes["@mozilla.org/filepicker;1"].createInstance(nsIFilePicker);
    fp.init(window, strbundle.getString("saveFileAs"), nsIFilePicker.modeSave);
    fp.appendFilter(strbundle.getString("FreeMindTemplate"),"*.mm");
    fp.defaultString = clearString(suggest) + ".mm";
    var res = fp.show();
    if ((res != nsIFilePicker.returnOK) && (res != nsIFilePicker.returnReplace)) {
      return false;
    }
    var filename = fp.file.path;
    var test = filename.split(".");
    if (test[test.length - 1] != "mm") {
      filename += ".mm";
    }
    myDoc.writeXMLtoFile(element, filename, false);
  } catch(e) {
    alert("exportToFreeMind: " + e.message);
    return false;
  }

  alert(strbundle.getString("saveSuccessFreeMind") + " " + filename);

  return true;
}

//

function fixOldQSOS(xml) {
    var xslt = parseXML(fix_qsos_1_X);
    var error = xslt.getElementsByTagName("parsererror");
    if (error.length == 1) {
      alert("An error occurred while parsing the XSLT! This is a bug. Please report it using this description:\nThe QSOS 1.X fix XSLT doesn't work.\nPlease include your evaluations in the report.");
      alert("loadFile: " + strbundle.getString("parsingError") + "\n\n" + error[0].textContent);
      return false;
    }

    // FIXME find a way to open the right XSLT from the extension
    /*xslt = loadXSLT("chrome://qsos-xuled/content/freemind_to_qsos.xsl");
     if (xslt == null) {
     return false;
     }*/

    myDoc = new Document();

    try {
      var processor = new XSLTProcessor();
      processor.importStylesheet(xslt);
      myDoc.setSheet(processor.transformToDocument(xml));
    } catch (e) {
      alert("fixOldQSOS: can't process sheet: " + e.message);
      closeFile();
      return false;
    }

//     try {
//       setupEditorForEval();
//     } catch (e) {
//       alert("updateFromOldQSOS: an error occured while setting up the editor: " + e.message);
//       closeFile();
//       return false;
//     }

    docHasChanged();
}


////////////////////////////////////////////////////////////////////////////////

// Commands used to produce "Javascript compliant" strings form "raw" xslt files:
// sed 's/"/\\"/g' <file.xslt> | sed 's/$/\\/g'

// Last updated: 06/01/2013
var fix_qsos_1_X = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\
<xsl:stylesheet xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" version=\"1.0\">\
<xsl:output method=\"xml\" indent=\"yes\" encoding=\"UTF-8\"/>\
\
  <xsl:template match=\"document\">\
    <xsl:element name=\"document\">\
      <xsl:apply-templates select=\"header\"/>\
      <xsl:apply-templates select=\"section\"/>\
    </xsl:element>\
  </xsl:template>\
\
  <xsl:template match=\"header\">\
    <xsl:element name=\"header\">\
      <xsl:apply-templates select=\"authors\"/>\
      <xsl:apply-templates select=\"dates\"/>\
      <xsl:apply-templates select=\"appname\"/>\
      <xsl:apply-templates select=\"desc\"/>\
      <xsl:apply-templates select=\"release\"/>\
      <xsl:apply-templates select=\"licenseid\"/>\
      <xsl:apply-templates select=\"licensedesc\"/>\
      <xsl:apply-templates select=\"url\"/>\
      <xsl:apply-templates select=\"demourl\"/>\
      <xsl:apply-templates select=\"language\"/>\
      <qsosappname></qsosappname>\
      <xsl:apply-templates select=\"qsosformat\"/>\
      <xsl:apply-templates select=\"qsosspecificformat\"/>\
      <xsl:apply-templates select=\"qsosappfamily\"/>\
    </xsl:element>\
  </xsl:template>\
\
  <xsl:template match=\"authors\">\
    <authors>\
      <xsl:apply-templates select=\"author\"/>\
    </authors>\
  </xsl:template>\
\
  <xsl:template match=\"author\">\
    <author>\
      <xsl:apply-templates select=\"name\"/>\
      <xsl:apply-templates select=\"email\"/>\
    </author>\
  </xsl:template>\
\
  <xsl:template match=\"dates\">\
    <dates>\
      <xsl:apply-templates select=\"creation\"/>\
      <xsl:apply-templates select=\"validation\"/>\
    </dates>\
  </xsl:template>\
\
  <xsl:template match=\"@*|node()\">\
    <xsl:copy><xsl:apply-templates select=\"@*|node()\"/></xsl:copy>\
  </xsl:template>\
\
</xsl:stylesheet>";


////////////////////////////////////////////////////////////////////////////////
// Last updated: 01/01/2013
var template_to_qsos_2_0_fr = "<?xml version=\"1.0\"?>\
<xsl:stylesheet xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" version=\"1.0\">\
<xsl:output method=\"xml\" indent=\"yes\" encoding=\"UTF-8\"/>\
\
  <xsl:template match=\"map\">\
    <xsl:element name=\"document\">\
      <xsl:apply-templates select=\"node\"/>\
    </xsl:element>\
  </xsl:template>\
\
  <xsl:template match=\"node\">\
    <xsl:choose>\
      <xsl:when test=\"parent::map\">\
	<header>\
	    <xsl:apply-templates select=\"//node[@ID='authors']\"/>\
	    <dates>\
	      <creation><xsl:value-of select=\"//node[@ID='creation_entry']/@TEXT\"/></creation>\
	      <validation><xsl:value-of select=\"//node[@ID='update_entry']/@TEXT\"/></validation>\
	    </dates>\
	    <appname></appname>\
	    <desc></desc>\
	    <release></release>\
	    <licenseid></licenseid>\
	    <licensedesc></licensedesc>\
	    <url></url>\
	    <demourl></demourl>\
	    <language><xsl:value-of select=\"//node[@ID='language_entry']/@TEXT\"/></language>\
	    <qsosappname></qsosappname>\
	    <qsosformat>1.0</qsosformat>\
	    <qsosspecificformat><xsl:value-of select=\"//node[@ID='version_entry']/@TEXT\"/></qsosspecificformat>\
	    <qsosappfamily><xsl:value-of select=\"@TEXT\"/></qsosappfamily>\
	</header>\
	<xsl:apply-templates select=\"node\"/>\
      </xsl:when>\
      <xsl:when test=\"./@ID='maturity'\">\
	<section name=\"maturity\" title=\"Maturité\">\
	  <desc>Maturité du projet en charge du développement et de la maintenance du logiciel</desc>\
	  <element name=\"legacy\" title=\"Patrimoine\">\
	    <desc>Historique et patrimoine du projet</desc>\
	    <element name=\"age\" title=\"Age du projet\">\
	      <desc0>Inférieur à trois mois</desc0>\
	      <desc1>Entre trois mois et trois ans</desc1>\
	      <desc2>Supérieur à trois ans</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"historyknowproblems\" title=\"Historique\">\
	      <desc0>Le logiciel connaît de nombreux problèmes qui peuvent être rédhibitoires</desc0>\
	      <desc1>Pas de problèmes majeurs, ni de crise ou historique inconnu</desc1>\
	      <desc2>Bon historique de gestion de projet et de crise</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"developersidentificationturnover\" title=\"Equipe de développement\">\
	      <desc0>Très peu de développeurs identifiés ou développeur unique</desc0>\
	      <desc1>Quelques développeurs actifs</desc1>\
	      <desc2>Equipe de développement importante et identifiée</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"popularity\" title=\"Popularité\">\
	      <desc0>Très peu d'utilisateurs identifiés</desc0>\
	      <desc1>Usage décelable</desc1>\
	      <desc2>Nombreux utilisateurs et références</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	  </element>\
	  <element name=\"activity\" title=\"Activité\">\
	    <desc>Activité du et autour du projet</desc>\
	    <element name=\"contributingcommunity\" title=\"Communauté des contributeurs\">\
	      <desc0>Pas de communauté ou de réelle activité (forum, liste de diffusion...)</desc0>\
	      <desc1>Communauté existante avec une activité notable</desc1>\
	      <desc2>Communauté forte : grosse activité sur les forums, de nombreux contributeurs et défenseurs</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"activityonbugs\" title=\"Activité autour des bugs\">\
	      <desc0>Réactivité faible sur le forum ou sur la liste de diffusion, ou rien au sujet des corrections de bugs dans les notes de versions</desc0>\
	      <desc1>Activité détectable mais sans processus clairement exposé, temps de résolution long</desc1>\
	      <desc2>Forte réactivité, basée sur des rôles et des assignations de tâches</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"activityonfunctionalities\" title=\"Activité autour des fonctionnalités\">\
	      <desc0>Pas ou peu de nouvelles fonctionnalités</desc0>\
	      <desc1>Évolution du produit conduite par une équipe dédiée ou par des utilisateurs, mais sans processus clairement exposé</desc1>\
	      <desc2>Les requêtes pour les nouvelles fonctionnalités sont clairement outillées, feuille de route disponible</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"activityonreleases\" title=\"Activité sur les releases/versions\">\
	      <desc0>Très faible activité que ce soit sur les versions de production ou de développement (alpha, beta)</desc0>\
	      <desc1>Activité que ce soit sur les versions de production ou de développement (alpha, beta), avec des versions correctives mineures fréquentes</desc1>\
	      <desc2>Activité importante avec des versions correctives fréquentes et des versions majeures planifiées liées aux prévisions de la feuille de route</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	  </element>\
	  <element name=\"strategy\" title=\"Gouvernance\">\
	    <desc>Stratégie du projet</desc>\
	    <element name=\"copyrightowners\" title=\"Détenteur des droits\">\
	      <desc0>Les droits sont détenus par quelques individus ou entités commerciales</desc0>\
	      <desc1>Les droits sont détenus par de nombreux individus de façon homogène</desc1>\
	      <desc2>Les droits sont détenus par une entité légale, une fondation dans laquelle la communauté a confiance (ex: FSF, Apache, ObjectWeb)</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"roadmap\" title=\"Feuille de route\">\
	      <desc0>Pas de feuille de route publiée</desc0>\
	      <desc1>Feuille de route sans planning</desc1>\
	      <desc2>Feuille de route versionnée, avec planning et mesures de retard</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"ID_740641571\" title=\"Pilotage du projet\">\
	      <desc0>Pas de pilotage clair du projet</desc0>\
	      <desc1>Pilotage dicté par un seul individu ou une entité commerciale</desc1>\
	      <desc2>Indépendance forte de l'équipe de développement, droits détenus par une entité reconnue</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"ID_548761152\" title=\"Mode de distribution\">\
	      <desc0>Existence d'une distribution commerciale ou propriétaire ou distribution libre limitée fonctionnellement</desc0>\
	      <desc1>Sous-partie du logiciel disponible sous licence propriétaire (Coeur / Greffons...)</desc1>\
	      <desc2>Distribution totalement ouverte et libre</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	  </element>\
	  <element name=\"industrializedsolution\" title=\"Industrialisation\">\
	    <desc>Niveau d'industrialisation du projet</desc>\
	    <element name=\"services\" title=\"Services\">\
	      <desc>Offres de services (Support, Formation, Audit...)</desc>\
	      <desc0>Pas d'offre de service identifiée</desc0>\
	      <desc1>Offre existante mais restreinte géographiquement ou en une seule langue ou fournie par un seul fournisseur ou sans garantie</desc1>\
	      <desc2>Offre riche, plusieurs fournisseurs, avec des garanties de résultats</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"documentation\" title=\"Documentation\">\
	      <desc0>Pas de documentation utilisateur</desc0>\
	      <desc1>La documentation existe mais est en partie obsolète ou restreinte à une seule langue ou peu détaillée</desc1>\
	      <desc2>Documentation à jour, traduite et éventuellement adaptée à différentes cibles de lecteurs (enduser, sysadmin, manager...)</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"qualityassurance\" title=\"Méthode qualité\">\
	      <desc>Processus et méthode qualité</desc>\
	      <desc0>Pas de processus qualité identifié</desc0>\
	      <desc1>Processus qualité existant, mais non formalisé ou non outillé</desc1>\
	      <desc2>Processus qualité basé sur l'utilisation d'outils et de méthodologies standards</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"modificationofsourcecode\" title=\"Modification du code\">\
	      <desc0>Pas de moyen pratique de proposer des modifications de code</desc0>\
	      <desc1>Des outils sont fournis pour accéder et modifier le code (ex : CVS, SVN) mais ne sont pas vraiment utilisés pour développer le produit</desc1>\
	      <desc2>Le processus de modification de code est bien défini, exposé et respecté, basé sur des rôles bien définis</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	  </element>\
	</section>\
      </xsl:when>\
      <xsl:when test=\"./@ID='metadata'\"></xsl:when>\
      <xsl:when test=\"@ID = 'authors'\">\
	<authors>\
	  <xsl:apply-templates select=\"node\"/>\
	</authors>\
      </xsl:when>\
      <xsl:when test=\"@TEXT = 'author' and ancestor::node/@ID = 'authors'\">\
	<author>\
	  <xsl:apply-templates select=\"node\"/>\
	</author>\
      </xsl:when>\
      <xsl:when test=\"@TEXT = 'name' and ancestor::node/ancestor::node/@ID = 'authors'\">\
	  <name><xsl:value-of select=\"child::node/@TEXT\"/></name>\
      </xsl:when>\
      <xsl:when test=\"@TEXT = 'email' and ancestor::node/ancestor::node/@ID = 'authors'\">\
	  <email><xsl:value-of select=\"child::node/@TEXT\"/></email>\
      </xsl:when>\
      <xsl:when test=\"./@BACKGROUND_COLOR='#ffcccc'\"></xsl:when>\
      <xsl:when test=\"./@STYLE='bubble'\">\
	<desc><xsl:value-of select=\"@TEXT\"/></desc>\
      </xsl:when>\
      <xsl:when test=\"child::icon\">\
      	<xsl:if test=\"icon/@BUILTIN = 'full-0'\"><desc0><xsl:value-of select=\"@TEXT\"/></desc0></xsl:if>\
      	<xsl:if test=\"icon/@BUILTIN = 'full-1'\"><desc1><xsl:value-of select=\"@TEXT\"/></desc1></xsl:if>\
      	<xsl:if test=\"icon/@BUILTIN = 'full-2'\"><desc2><xsl:value-of select=\"@TEXT\"/></desc2></xsl:if>\
      </xsl:when>\
      <xsl:when test=\"count(ancestor::node()) = 3\">\
        <section name=\"{@ID}\" title=\"{@TEXT}\">\
          <xsl:apply-templates select=\"attribute\"/>\
	  <xsl:apply-templates select=\"node\"/>\
         </section>\
      </xsl:when>\
      <xsl:otherwise>\
        <element name=\"{@ID}\" title=\"{@TEXT}\">\
	  <xsl:apply-templates select=\"attribute\"/>\
	  <xsl:apply-templates select=\"node\"/>\
	  <xsl:if test=\"child::node/icon\">\
	  	<comment></comment>\
	  	<score></score>\
	  </xsl:if>\
        </element>\
      </xsl:otherwise>\
    </xsl:choose>\
  </xsl:template>\
  \
  <xsl:template match=\"attribute\">\
    <xsl:element name=\"{@NAME}\">\
      <xsl:value-of select=\"@VALUE\"/>\
    </xsl:element>\
  </xsl:template>\
  \
</xsl:stylesheet>";

////////////////////////////////////////////////////////////////////////////////
// Last updated: 06/01/2013
var template_to_qsos_2_0_en = "<?xml version=\"1.0\"?>\
<xsl:stylesheet xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" version=\"1.0\">\
<xsl:output method=\"xml\" indent=\"yes\" encoding=\"UTF-8\"/>\
\
  <xsl:template match=\"map\">\
    <xsl:element name=\"document\">\
      <xsl:apply-templates select=\"node\"/>\
    </xsl:element>\
  </xsl:template>\
\
  <xsl:template match=\"node\">\
    <xsl:choose>\
      <xsl:when test=\"parent::map\">\
	<header>\
	    <xsl:apply-templates select=\"//node[@ID='authors']\"/>\
	    <dates>\
	      <creation><xsl:value-of select=\"//node[@ID='creation_entry']/@TEXT\"/></creation>\
	      <validation><xsl:value-of select=\"//node[@ID='update_entry']/@TEXT\"/></validation>\
	    </dates>\
	    <appname></appname>\
	    <desc></desc>\
	    <release></release>\
	    <licenseid></licenseid>\
	    <licensedesc></licensedesc>\
	    <url></url>\
	    <demourl></demourl>\
	    <language><xsl:value-of select=\"//node[@ID='language_entry']/@TEXT\"/></language>\
	    <qsosappname></qsosappname>\
	    <qsosformat>1.0</qsosformat>\
	    <qsosspecificformat><xsl:value-of select=\"//node[@ID='version_entry']/@TEXT\"/></qsosspecificformat>\
	    <qsosappfamily><xsl:value-of select=\"@TEXT\"/></qsosappfamily>\
	</header>\
	<xsl:apply-templates select=\"node\"/>\
      </xsl:when>\
      <xsl:when test=\"./@ID='maturity'\">\
	<section name=\"maturity\" title=\"Maturity\">\
	  <desc>Maturity of the project in charge of the product's development and maintenance</desc>\
	  <element name=\"legacy\" title=\"Legacy\">\
	    <desc>Project's history and heritage</desc>\
	    <element name=\"age\" title=\"Age\">\
	      <desc0>Less than three months</desc0>\
	      <desc1>between three months and three years</desc1>\
	      <desc2>More than three years</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"historyknowproblems\" title=\"History\">\
	      <desc0>The software has many problems which can be prohibitive</desc0>\
	      <desc1>No major crisis, or unknown history</desc1>\
	      <desc2>Good past experience in crisis management</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"developersidentificationturnover\" title=\"Core team\">\
	      <desc0>Very few identified croe developers</desc0>\
	      <desc1>Few active core developers</desc1>\
	      <desc2>Important core development team identified</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"popularity\" title=\"Popularity\">\
	      <desc0>Very few identified users</desc0>\
	      <desc1>Usage can be detected</desc1>\
	      <desc2>Many known users and references</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	  </element>\
	  <element name=\"activity\" title=\"Activity\">\
	    <desc>Activity inside and around the project</desc>\
	    <element name=\"contributingcommunity\" title=\"Contributing community\">\
	      <desc0>No real community nor activity (forum, mailing list...)</desc0>\
	      <desc1>Community with significant activity</desc1>\
	      <desc2>Strong community with vivid activity in forums, with many contributors and supporters</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"activityonbugs\" title=\"Activity on bugs\">\
	      <desc0>Low reactivity in forums and mailing lists, or no mention about bugfixes in release notes</desc0>\
	      <desc1>Existing activity but without any clearly defined process or with long resolution times</desc1>\
	      <desc2>Strong reactivity based on roles and task assignments</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"activityonfunctionalities\" title=\"Activity on features\">\
	      <desc0>Few or no new features</desc0>\
	      <desc1>Product's evolution is led by a dedicated team or by users, but without a clearly stated process</desc1>\
	      <desc2>Feature request process is industiralized, an associated roadmap is available</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"activityonreleases\" title=\"Activity on releases/versions\">\
	      <desc0>Very low activity on the production or development versions (alpha, beta)</desc0>\
	      <desc1>Activity on production or development versions (alpha, beta), with frequent minor corrective versions</desc1>\
	      <desc2>Important activity with frequent corrective versions and planned major versions linked with the roadmap</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	  </element>\
	  <element name=\"strategy\" title=\"Governance\">\
	    <desc>Project Strategy</desc>\
	    <element name=\"copyrightowners\" title=\"Copyright owners\">\
	      <desc0>Rights are held by a few individuals or commercial entities</desc0>\
	      <desc1>Rights are uniformly held by many individuals</desc1>\
	      <desc2>Rights are held by a legal entity or a foundation in which the community trust (eg FSF, Apache, ObjectWeb)</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"roadmap\" title=\"Roadmap\">\
	      <desc0>No roadmap published</desc0>\
	      <desc1>Roadmap without planning</desc1>\
	      <desc2>Versioned roadmap with planning and delay measurements</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"ID_740641571\" title=\"Project management\">\
	      <desc0>No clear and apparent project management</desc0>\
	      <desc1>Project managed by an individual or a single commercial entity</desc1>\
	      <desc2>strong independance of core team, rights held by a recognized entity</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"ID_548761152\" title=\"Distribution mode\">\
	      <desc0>Dual distribution with a commercial version along with a functionally limited free one</desc0>\
	      <desc1>Subparts are only available under a proprietary license (core, plugins...)</desc1>\
	      <desc2>Completely open and free distribution</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	  </element>\
	  <element name=\"industrializedsolution\" title=\"Industrialization\">\
	    <desc>Industrialization level of the project</desc>\
	    <element name=\"services\" title=\"Services\">\
	      <desc>Existing service offerings (support, training, audit...)</desc>\
	      <desc0>No service offering identified</desc0>\
	      <desc1>Limited service offering (geographically, to a single language, to a single supplier or without warranty</desc1>\
	      <desc2>Rich ecosystem of services provided by multiple suppliers, with guaranteed results</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"documentation\" title=\"Documentation\">\
	      <desc0>No user documentation</desc0>\
	      <desc1>Documentation exists but is partly obsolete or restricted to one language or to few details</desc1>\
	      <desc2>Documentation up to date, translated and possibly adapted to several target readers (enduser, sysadmin, manager...)</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"qualityassurance\" title=\"Quality assurance\">\
	      <desc>QA process</desc>\
	      <desc0>No QA process identified</desc0>\
	      <desc1>Existing QA processes, but they are not formalized or equipped</desc1>\
	      <desc2>QA process based on standard tools and methodologies</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	    <element name=\"modificationofsourcecode\" title=\"Source code modification\">\
	      <desc0>No convenient way to propose source code modifications</desc0>\
	      <desc1>Tools are provided to access and modify the code (eg SCM, forge...) but are not really used by core team to develop the product</desc1>\
	      <desc2>The contributing process is well defined, exposed and respected, it is based on clearly defined roles</desc2>\
	      <comment/>\
	      <score/>\
	    </element>\
	  </element>\
	</section>\
      </xsl:when>\
      <xsl:when test=\"./@ID='metadata'\"></xsl:when>\
      <xsl:when test=\"@ID = 'authors'\">\
	<authors>\
	  <xsl:apply-templates select=\"node\"/>\
	</authors>\
      </xsl:when>\
      <xsl:when test=\"@TEXT = 'author' and ancestor::node/@ID = 'authors'\">\
	<author>\
	  <xsl:apply-templates select=\"node\"/>\
	</author>\
      </xsl:when>\
      <xsl:when test=\"@TEXT = 'name' and ancestor::node/ancestor::node/@ID = 'authors'\">\
	  <name><xsl:value-of select=\"child::node/@TEXT\"/></name>\
      </xsl:when>\
      <xsl:when test=\"@TEXT = 'email' and ancestor::node/ancestor::node/@ID = 'authors'\">\
	  <email><xsl:value-of select=\"child::node/@TEXT\"/></email>\
      </xsl:when>\
      <xsl:when test=\"./@BACKGROUND_COLOR='#ffcccc'\"></xsl:when>\
      <xsl:when test=\"./@STYLE='bubble'\">\
	<desc><xsl:value-of select=\"@TEXT\"/></desc>\
      </xsl:when>\
      <xsl:when test=\"child::icon\">\
      	<xsl:if test=\"icon/@BUILTIN = 'full-0'\"><desc0><xsl:value-of select=\"@TEXT\"/></desc0></xsl:if>\
      	<xsl:if test=\"icon/@BUILTIN = 'full-1'\"><desc1><xsl:value-of select=\"@TEXT\"/></desc1></xsl:if>\
      	<xsl:if test=\"icon/@BUILTIN = 'full-2'\"><desc2><xsl:value-of select=\"@TEXT\"/></desc2></xsl:if>\
      </xsl:when>\
      <xsl:when test=\"count(ancestor::node()) = 3\">\
        <section name=\"{@ID}\" title=\"{@TEXT}\">\
          <xsl:apply-templates select=\"attribute\"/>\
	  <xsl:apply-templates select=\"node\"/>\
         </section>\
      </xsl:when>\
      <xsl:otherwise>\
        <element name=\"{@ID}\" title=\"{@TEXT}\">\
	  <xsl:apply-templates select=\"attribute\"/>\
	  <xsl:apply-templates select=\"node\"/>\
	  <xsl:if test=\"child::node/icon\">\
	  	<comment></comment>\
	  	<score></score>\
	  </xsl:if>\
        </element>\
      </xsl:otherwise>\
    </xsl:choose>\
  </xsl:template>\
  \
  <xsl:template match=\"attribute\">\
    <xsl:element name=\"{@NAME}\">\
      <xsl:value-of select=\"@VALUE\"/>\
    </xsl:element>\
  </xsl:template>\
  \
</xsl:stylesheet>";

////////////////////////////////////////////////////////////////////////////////
// Last updated: 29/07/2011
var qsos_2_0_to_freemind_template = "<xsl:stylesheet xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" version=\"1.0\">\
<xsl:output method=\"xml\" indent=\"yes\" encoding=\"UTF-8\" omit-xml-declaration=\"yes\"/>\
\
<xsl:template match=\"document\">\
<xsl:element name=\"map\">\
<xsl:attribute name=\"version\">0.9.0</xsl:attribute>\
<xsl:element name=\"node\">\
<xsl:attribute name=\"ID\">type</xsl:attribute>\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/template/type\"/></xsl:attribute>\
<xsl:apply-templates select=\"section\"/>\
<node CREATED=\"1311176248788\" ID=\"metadata\" MODIFIED=\"1311176284873\" POSITION=\"right\" TEXT=\"Metadata\">\
<font NAME=\"SansSerif\" BOLD=\"true\" SIZE=\"12\"/>\
<node BACKGROUND_COLOR=\"#ffcccc\" CREATED=\"1311176012521\" ID=\"ID_424723663\" MODIFIED=\"1311234530138\" STYLE=\"bubble\">\
<richcontent TYPE=\"NODE\"><html><head></head>\
<body>\
<xsl:choose>\
<xsl:when test=\"qsosMetadata/language = 'FR'\">\
<p>\
<i>Cette zone est r&#xE9;serv&#xE9;e aux meta donn&#xE9;es relatives au template. </i>\
</p>\
<p><i>Merci de la compl&#xE9;ter si n&#xE9;cessaire, notamment si vous en modifier la structure et le contenu des autres axes, pensez alors &#xE0; mettre &#xE0; jour la version, la date de mise &#xE0; jour (</i>update<i>) et les auteurs (</i>authors<i>).</i></p>\
</xsl:when>\
<xsl:otherwise>\
<p>\
<i>This zone is dedicated to template metadata. </i>\
</p>\
<p><i>Please fill when you modify the template's structure or contents and do not forget to update here the followwing metadata: version, updtae date, authors (if you'r a new author, add a new entry).</i></p>\
</xsl:otherwise>\
</xsl:choose>\
</body>\
</html>\
</richcontent>\
<icon BUILTIN=\"messagebox_warning\"/>\
</node>\
<node CREATED=\"1311176592584\" ID=\"version\" MODIFIED=\"1311177836867\" TEXT=\"version\">\
<node CREATED=\"1311176605382\" ID=\"version_entry\" MODIFIED=\"1311234866394\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/template/version\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
<node CREATED=\"1311177840941\" ID=\"language\" MODIFIED=\"1311177848879\" TEXT=\"language\">\
<node CREATED=\"1311176605382\" ID=\"language_entry\" MODIFIED=\"1311234870295\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/language\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
<node CREATED=\"1311176311468\" ID=\"authors\" MODIFIED=\"1311176319000\" TEXT=\"authors\">\
<xsl:apply-templates select=\"qsosMetadata/template/authors/author\"/>\
</node>\
<node CREATED=\"1311176321071\" ID=\"reviewer\" MODIFIED=\"1311176511407\" TEXT=\"reviewer\">\
<node CREATED=\"1311176326649\" ID=\"reviewer_name\" MODIFIED=\"1311176329456\" TEXT=\"name\">\
<node CREATED=\"1311176352743\" ID=\"reviewer_name_entry\" MODIFIED=\"1311234911615\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/template/reviewer/name\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
<node CREATED=\"1311176333060\" ID=\"reviewer_email\" MODIFIED=\"1311176335780\" TEXT=\"email\">\
<node CREATED=\"1311176383595\" ID=\"reviewer_email_entry\" MODIFIED=\"1311234898697\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/template/reviewer/email\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
<node CREATED=\"1311176348053\" ID=\"reviewer_comment\" MODIFIED=\"1311176350924\" TEXT=\"comment\">\
<node CREATED=\"1311176394385\" ID=\"reviewer_comment_entry\" MODIFIED=\"1311234903352\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/template/reviewer/comment\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
<node CREATED=\"1311176549844\" ID=\"review_date\" MODIFIED=\"1311176554478\" TEXT=\"reviewDate\">\
<node CREATED=\"1311176394385\" ID=\"review_date_entry\" MODIFIED=\"1311234907914\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/template/reviewer/reviewDate\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
</node>\
<node CREATED=\"1311176675856\" ID=\"dates\" MODIFIED=\"1311176680299\" TEXT=\"dates\">\
<node CREATED=\"1311176682141\" ID=\"creation\" MODIFIED=\"1311176685329\" TEXT=\"creation\">\
<node CREATED=\"1311176696701\" ID=\"creation_entry\" MODIFIED=\"1311234932081\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/template/dates/creation\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
<node CREATED=\"1311176691625\" ID=\"update\" MODIFIED=\"1311176695012\" TEXT=\"update\">\
<node CREATED=\"1311176696701\" ID=\"update_entry\" MODIFIED=\"1311234935855\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"qsosMetadata/template/dates/creation\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
</node>\
</node>\
</xsl:element>\
</xsl:element>\
</xsl:template>\
\
<xsl:template match=\"section\">\
<node ID=\"{@name}\" TEXT=\"{@title}\">\
<xsl:if test=\"position() mod 2 = 0\">\
<xsl:attribute name=\"POSITION\">left</xsl:attribute>\
</xsl:if>\
<xsl:if test=\"position() mod 2 = 1\">\
<xsl:attribute name=\"POSITION\">right</xsl:attribute>\
</xsl:if>\
<font NAME=\"SansSerif\" BOLD=\"true\" SIZE=\"12\"/>\
<xsl:element name=\"node\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"desc\"/></xsl:attribute>\
<xsl:attribute name=\"STYLE\">bubble</xsl:attribute>\
<font NAME=\"SansSerif\" ITALIC=\"true\" SIZE=\"10\"/>\
</xsl:element>\
<xsl:apply-templates select=\"element\"/>\
</node>\
</xsl:template>\
\
<xsl:template match=\"element\">\
<xsl:element name=\"node\">\
<xsl:attribute name=\"ID\"><xsl:value-of select=\"@name\"/></xsl:attribute>\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"@title\"/></xsl:attribute>\
\
<xsl:element name=\"node\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"desc\"/></xsl:attribute>\
<xsl:attribute name=\"STYLE\">bubble</xsl:attribute>\
<font NAME=\"SansSerif\" ITALIC=\"true\" SIZE=\"10\"/>\
</xsl:element>\
\
<xsl:if test = 'desc0'>\
<xsl:element name=\"node\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"desc0\"/></xsl:attribute>\
<icon BUILTIN=\"full-0\"/>\
</xsl:element>\
<xsl:element name=\"node\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"desc1\"/></xsl:attribute>\
<icon BUILTIN=\"full-1\"/>\
</xsl:element>\
<xsl:element name=\"node\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"desc2\"/></xsl:attribute>\
<icon BUILTIN=\"full-2\"/>\
</xsl:element>\
</xsl:if>\
\
<xsl:apply-templates select=\"element\"/>\
\
</xsl:element>\
</xsl:template>  \
\
<xsl:template match=\"author\">\
<node CREATED=\"1311176321071\" ID=\"ID_1573932839\" MODIFIED=\"1311176324722\" TEXT=\"author\">\
<node CREATED=\"1311176326649\" ID=\"ID_451850163\" MODIFIED=\"1311176329456\" TEXT=\"name\">\
<node CREATED=\"1311176352743\" ID=\"ID_109036830\" MODIFIED=\"1311234875553\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"name\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
<node CREATED=\"1311176333060\" ID=\"ID_896894920\" MODIFIED=\"1311176335780\" TEXT=\"email\">\
<node CREATED=\"1311176383595\" ID=\"ID_550027674\" MODIFIED=\"1311234881668\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"email\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
<node CREATED=\"1311176348053\" ID=\"ID_1556413098\" MODIFIED=\"1311176350924\" TEXT=\"comment\">\
<node CREATED=\"1311176394385\" ID=\"ID_1447602460\" MODIFIED=\"1311176456353\" STYLE=\"bubble\">\
<xsl:attribute name=\"TEXT\"><xsl:value-of select=\"comment\"/></xsl:attribute>\
<font ITALIC=\"true\" NAME=\"SansSerif\" SIZE=\"12\"/>\
</node>\
</node>\
</node>\
</xsl:template> \
\
</xsl:stylesheet>";
