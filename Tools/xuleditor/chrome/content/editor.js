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
**  editor.js: functions associated with the editor.xul file
**
*/

// Object "Document" representing data in the QSOS XML file
var myDoc;
// Indicator of document modification
var docChanged;
var evaluationOpen;
// id (actually "name" in the QSOS XML file) of the currently selected criteria in the tree
var id;
// Localized strings bundle
var strbundle;

// Window initialization after loading
function init() {
  strbundle = document.getElementById("properties");
  docChanged = false;
  setStateEvalOpen(false);
  freezeScore("true");
  freezeComments("true");

  // Parameters management
  var urlFirefox = window.arguments[1];
  if (urlFirefox) {
    // Case of a .qsos browsing redirection (cf. qsos-overlay.js)
    openRemoteFile(urlFirefox);
  } else {
    var cmdLine = window.arguments[0];
    cmdLine = cmdLine.QueryInterface(Components.interfaces.nsICommandLine); // FIXME
    var uri = cmdLine.handleFlagWithParam("file", false);
    if (uri) {
      // Case of a .qsos file passed in parameter through commandline (xuleditor -file filename)
      uri = cmdLine.resolveURI(uri);
      openRemoteFile(uri.spec);
    }
  }
}


////////////////////////////////////////////////////////////////////
// Helper functions
////////////////////////////////////////////////////////////////////


// Get privilege to open windows
function getPrivilege() {
  try {
    //netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
    return true;
  } catch (e) {
    alert("newFile: Permission to open file denied: " + e.message);
    return false;
  }
}


function exitConfirmDialog() {
  getPrivilege();
  try {
    var text = strbundle.getString("exitAnyway");
    window.openDialog('chrome://qsos-xuled/content/confirm.xul', 'Confirm', 'chrome,dialog,modal', text, saveFile, saveFileAs);
  } catch (e) {
    alert("There is a problem here: " + e.message);
  }
}


// Toogle the state of the editor between "eval opened" and "eval closed"
// The general, criteria and chart tabs are open only if a document is opened.
function setStateEvalOpen(state) {
  evaluationOpen = state;
  if (state) {
    var bool = "";
  } else {
    var bool = "true";
  }
  document.getElementById("generalTab").hidden = bool;
  document.getElementById("criteriaTab").hidden = bool;
  document.getElementById("chartTab").hidden = bool;
  document.getElementById("updateLocalFile").disabled = bool;
  document.getElementById("updateRemoteFile").disabled = bool;
  if (!state) { document.getElementById("saveFile").disabled = bool; }
  document.getElementById("saveFileAs").disabled = bool;
  document.getElementById("saveRemoteFile").disabled = bool;
  document.getElementById("closeFile").disabled = bool;
}

// (Un)freezes the "Score" input files (current criteria properties)
// bool: "true" to freeze; "" to unfreeze
function freezeScore(bool) {
  document.getElementById("f-c-score").disabled = bool;
}

// (Un)freezes the "Comments" input file (current criteria property)
// bool: "true" to freeze; "" to unfreeze
function freezeComments(bool) {
  document.getElementById("f-c-comments").disabled = bool;
}
