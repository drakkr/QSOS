/*
 **  Copyright (C) 2006-2013 Atos
 **
 **  Authors: Raphael Semeteys <raphael.semeteys@atos.net>
 **           Timothée Ravier <timothee.romain.ravier@gmail.com>
 **
 **  This program is free software; you can redistribute it and/or modify
 **  it under the terms of the GNU General Public License as published by
 **  the Free Software Foundation; either version 2 of the License, or
 **  (at your option) any later version.
 **
 **  This program is distributed in the hope that it will be useful,
 **  but WITHOUT ANY WARRANTY; without even the implied warranty of
 **  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 **  GNU General Public License for more details.
 **
 **  You should have received a copy of the GNU General Public License
 **  along with this program; if not, write to the Free Software
 **  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 **
 **
 **  QSOS XUL Editor
 **  new.js: functions associated with the new file dialog
 **
 */

var xmlDoc;
var backend;

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
  var loadremote = backend + prefManager.getCharPref("extensions.qsos-xuled.loadremote-tpl");

  req = new XMLHttpRequest();
  req.open('GET', loadremote, false);
  //req.overrideMimeType('text/xml');
  req.send(null);

  var domParser = new DOMParser();
  xmlDoc = domParser.parseFromString(req.responseText, "text/xml");

  var evalTree = document.getElementById("evalTree");
  
  var defaultLocale = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefService).getDefaultBranch("general.useragent.").getComplexValue("locale",
      Components.interfaces.nsIPrefLocalizedString).data.substr(0, 2);

  var treechildren = getList("master",defaultLocale);
  evalTree.replaceChild(treechildren, document.getElementById("myTreechildren"));
}

//Check if double click should fire something
function checkLabel() {
  var evalTree = document.getElementById("evalTree");
  var file = evalTree.view.getItemAtIndex(evalTree.currentIndex).firstChild.firstChild.getAttribute("id");

  if (file.substr(-3) == ".mm") {
    document.getElementById("New").acceptDialog();
  }
}

function doOK() {
  var evalTree = document.getElementById("evalTree");
  var file = evalTree.view.getItemAtIndex(evalTree.currentIndex).firstChild.firstChild.getAttribute("id");
  var repo = document.getElementById("listrepo").selectedItem.value;

  var url = backend+repo+"/"+file;
  
  try {
    req = new XMLHttpRequest();

    req.open('GET', url, false);
    req.overrideMimeType('text/xml');
    req.send(null);
    var domParser = new DOMParser();
    template = domParser.parseFromString(req.responseText, "text/xml");
    
    //Call window opener callback function
    window.arguments[1](template, document.getElementById("listlang").selectedItem.value);
    
    //alert(template.toString());
    
    //newFileFromTemplate(template);
  } catch (e) {
    alert("doOK: " + e.message);
    return false;
  }

  //if (url.substr(0, 7) != "http://") url = "";

}


function getList(repo, lang) {
  var treechildren = document.createElement("treechildren");
  treechildren.setAttribute("id", "myTreechildren");

  var items = xmlDoc.evaluate("/templates/item[@repo='"+repo+"' and @language='"+lang+"']", xmlDoc, null, XPathResult.ANY_TYPE,null);
  var item = items.iterateNext();
  while (item) {
    
    
    var treeitem = document.createElement("treeitem");
    //treeitem.setAttribute("container", "true");
    //treeitem.setAttribute("open", "true");
    var treerow = document.createElement("treerow");
    
    var treecell = document.createElement("treecell");
    treecell.setAttribute("id", item.getAttribute("id"));
    treecell.setAttribute("label", item.getAttribute("name"));
    treerow.appendChild(treecell);
    
    treecell = document.createElement("treecell");
    treecell.setAttribute("label", item.getAttribute("version"));
    treerow.appendChild(treecell); 
    
    treecell = document.createElement("treecell");
    treecell.setAttribute("label", item.getAttribute("language"));
    treerow.appendChild(treecell);
    
    treecell = document.createElement("treecell");
    treecell.setAttribute("label", item.getAttribute("repo"));
    treerow.appendChild(treecell);
    
    treeitem.appendChild(treerow);           
    treechildren.appendChild(treeitem);
    item = items.iterateNext();
  }
  return treechildren;
}

function updateList() {
  var evalTree = document.getElementById("evalTree");
  var repo = document.getElementById("listrepo").selectedItem.value;
  var lang = document.getElementById("listlang").selectedItem.value;  
  
  evalTree.replaceChild(getList(repo, lang), document.getElementById("myTreechildren"));
}