/*
**  Copyright (C) 2006-2013 Atos
**
**  Authors: Raphael Semeteys <raphael.semeteys@atos.net>
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
** QSOS XUL Editor
** load.js: functions associated with the load remote file dialog
*/

//QSOS backend containing the list of available evaluations
var xmlDoc;
var backend;

//Connection to QSOS backend and generation of the tree view
function init() {
  try {
    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
  } catch (e) {
    alert("newFile: Permission to open file denied: " + e.message);
    return false;
  }

  var prefManager = Components.classes["@mozilla.org/preferences-service;1"]
          .getService(Components.interfaces.nsIPrefBranch);
  
  backend = prefManager.getCharPref("extensions.qsos-xuled.backend");
  var loadremote = backend + prefManager.getCharPref("extensions.qsos-xuled.loadremote");

  req = new XMLHttpRequest();
  req.open('GET', loadremote, false);
  //req.overrideMimeType('text/xml');
  req.send(null);

  var output = req.responseText;
  var converter = Components.classes["@mozilla.org/intl/scriptableunicodeconverter"]
		.createInstance(Components.interfaces.nsIScriptableUnicodeConverter);
  converter.charset = "UTF-8";
  output = converter.ConvertFromUnicode(output);
  
  var domParser = new DOMParser();
  xmlDoc = domParser.parseFromString(output, "text/xml");

  var evalTree = document.getElementById("evalTree");
  
  var defaultLocale = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefService).getDefaultBranch("general.useragent.").getComplexValue("locale",
    Components.interfaces.nsIPrefLocalizedString).data.substr(0, 2);
    
  var criteria = getcomplextree("master",defaultLocale);    

  var treechildren = buildtree(criteria);
  evalTree.replaceChild(treechildren, document.getElementById("myTreechildren"));
}

//Generates a XUL tree from QSOS backend's list
function getcomplextree(repo, lang) {
  var criteria = new Array();
  var items = xmlDoc.evaluate("/children/item[@repo='"+repo+"' and @language='"+lang+"']", xmlDoc, null, XPathResult.ANY_TYPE,null);
  var item = items.iterateNext();
  while (item) {
    var criterion = new Object();
    criterion.id = item.getAttribute("id");
    criterion.name =  item.getAttribute("name");
    criterion.version =  item.getAttribute("version");
    criterion.language =  item.getAttribute("language")  
    criterion.repo =  item.getAttribute("repo");
    criterion.children = getsubcriteria(criterion.id);
    criteria.push(criterion);
    item = items.iterateNext();
  }
  return criteria;
}

//Recursive function used by getcomplextree()
function getsubcriteria(id) {
  var subcriteria = new Array();
  var items = xmlDoc.evaluate("//*[@id='"+id+"']/children/item", xmlDoc, null, XPathResult.ANY_TYPE,null);
  var item = items.iterateNext();
  while (item) {
    var criterion = new Object();
    criterion.id = item.getAttribute("id");
    criterion.name =  item.getAttribute("name");
    criterion.version =  item.getAttribute("version");
    criterion.language =  item.getAttribute("language")    
    criterion.repo =  item.getAttribute("repo");
    criterion.children = getsubcriteria(criterion.id);
    subcriteria.push(criterion);
    item = items.iterateNext();
  }
  if (subcriteria.length > 0) {
    return subcriteria;
  } else {
    return "null";
  }
}

function dump(input) {
  var serializer = new XMLSerializer();
  var xml = serializer.serializeToString(input);
  alert(xml);
}

//XUL Tree recursive creation function
function buildtree(criteria) {
  var treechildren = document.createElement("treechildren");
  treechildren.setAttribute("id", "myTreechildren");
  for (var i=0; i < criteria.length; i++) {
    treeitem = newtreeitem(criteria[i]);
    treechildren.appendChild(treeitem);
  }
  //dump(treechildren);
  return treechildren;
}

//XUL Tree recursive creation function
function newtreeitem(criterion) {
  var treeitem = document.createElement("treeitem");
  if (criterion.id.substr(-5) != ".qsos") {
    treeitem.setAttribute("container", "true");
    treeitem.setAttribute("open", "false");
  }
  var treerow = document.createElement("treerow");
  
  var treecell = document.createElement("treecell");
  treecell.setAttribute("id", criterion.id);
  treecell.setAttribute("label", criterion.name);
  treerow.appendChild(treecell);

  treecell = document.createElement("treecell");
  treecell.setAttribute("label", criterion.version);
  treerow.appendChild(treecell);
  
  treecell = document.createElement("treecell");
  treecell.setAttribute("label", criterion.language);
  treerow.appendChild(treecell);
  
  treecell = document.createElement("treecell");
  treecell.setAttribute("label", criterion.repo);
  treerow.appendChild(treecell);
  
  treeitem.appendChild(treerow);
  if (criterion.children != "null") treeitem.appendChild(buildsubtree(criterion.children));
  return treeitem;
}

//XUL Tree recursive creation function
function buildsubtree(criteria) {
  var treechildren = document.createElement("treechildren");
  for (var i=0; i < criteria.length; i++) {
    treeitem = newtreeitem(criteria[i]);
    treechildren.appendChild(treeitem);
  }
  return treechildren;
}

//Check if double click should fire something
function checkLabel() {
  var evalTree = document.getElementById("evalTree");
  var label = evalTree.view.getItemAtIndex(evalTree.currentIndex).firstChild.firstChild.getAttribute("id");

  if (label.substr(-5) == ".qsos") {
    document.getElementById("Load").acceptDialog();
  }
}

//Dialog's validation
function doOK() {
  var evalTree = document.getElementById("evalTree");
  var file = evalTree.view.getItemAtIndex(evalTree.currentIndex).firstChild.firstChild.getAttribute("id");
  var repo = document.getElementById("listrepo").selectedItem.value;
  
  var url = backend+repo+"/"+file;

  //Call window opener callback function
  window.arguments[1](url);
}

function updateList() {
  var evalTree = document.getElementById("evalTree");
  var repo = document.getElementById("listrepo").selectedItem.value;
  var lang = document.getElementById("listlang").selectedItem.value;  

  var criteria = getcomplextree(repo, lang);    
  var treechildren = buildtree(criteria);
  
  evalTree.replaceChild(treechildren, document.getElementById("myTreechildren"));
}