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
 **  general.js: functions associated with the general tab
 **
 */


function docHasChanged(bool) {
  if (bool == false){
    docChanged = false;
    document.getElementById("saveFile").disabled = "true";
  } else {
    docChanged = true;
    document.getElementById("saveFile").disabled = "";
  }
}

// Triggered when software name is modified
function changeAppName(xulelement) {
  myDoc.setappname(xulelement.value);
  docHasChanged();
}

// Triggered when software release is modified
function changeRelease(xulelement) {
  myDoc.setrelease(xulelement.value);
  docHasChanged();
}

// Triggered when software family is modified
function changeSoftwareFamily(xulelement) {
  myDoc.setqsosappfamily(xulelement.value);
  docHasChanged();
}

// Triggered when software license is modified
function changeLicense(list, id) {
  myDoc.setlicenseid(id);
  myDoc.setlicensedesc(list.selectedItem.getAttribute("label"));
  docHasChanged();
}

// Triggered when software description is modified
function changeDesc(xulelement) {
  myDoc.setdesc(xulelement.value);
  docHasChanged();
}

// Triggered when software URL is modified
function changeUrl(xulelement) {
  myDoc.seturl(xulelement.value);
  docHasChanged();
}

// Triggered when software demo URL is modified
function changeDemoUrl(xulelement) {
  myDoc.setdemourl(xulelement.value);
  docHasChanged();
}

// Triggered when an author is select in the list
function changeAuthor(author) {
  document.getElementById("f-a-name").value = author.label;
  document.getElementById("f-a-email").value = author.value;
}

// Triggered when an author is added
function addAuthor() {
  var mylist = document.getElementById("f-a-list");
  var listitem = document.createElement("listitem");
  var name = document.getElementById("f-a-name").value;
  var email = document.getElementById("f-a-email").value;
  if (name == "" || email == "") {
    alert("A valid name and e-mail adress are required");
  } else {
    for (var i = 0; i < mylist.getRowCount(); ++i) {
      if (mylist.getItemAtIndex(i).label == name) {
        alert("There already is someone named " + name);
        return;
      }
    }
    listitem.setAttribute("label", name);
    listitem.setAttribute("value", email);
    mylist.appendChild(listitem);
    myDoc.addauthor(name, email);
    docHasChanged();
    document.getElementById("delAuthorButton").disabled = "";
  }
}

// Triggered when an author is deleted
function deleteAuthor() {
  var mylist = document.getElementById("f-a-list");
  if (mylist.selectedItem == null) {
    alert("Select an author to be deleted");
    return;
  }
  if (mylist.getRowCount() <= 1) {
    document.getElementById("delAuthorButton").disabled = true;
    if (mylist.getRowCount() == 0) {
      alert("There isn't any author any more");
      return;
    }
  }
  mylist.removeChild(mylist.selectedItem);
  myDoc.delauthor(document.getElementById("f-a-name").value);
  document.getElementById("f-a-name").value = "";
  document.getElementById("f-a-email").value = "";
  docHasChanged();
}

function navigate(link) {
  var prefManager = Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch);
  var mode = prefManager.getCharPref("extensions.qsos-xuled.mode");
  
  if (mode == "ext") {
    document
      .getElementById("content")
      .webNavigation
      .loadURI(link, 0, null, null, null);
  } else {
    var uri = Components
      .classes["@mozilla.org/network/simple-uri;1"]
      .getService(Components.interfaces.nsIURI);

    uri.spec = link;

    Components
      .classes["@mozilla.org/uriloader/external-protocol-service;1"]
      .getService(Components.interfaces.nsIExternalProtocolService)
      .loadUrl(uri);
  }
}  
