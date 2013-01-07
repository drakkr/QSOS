/*
 * *  Copyright (C) 2006-2013 Atos
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
 **  criteria.js: functions associated with the criteria tab
 **
 */


// Forces the selection of element with id in the criteria tree
function selectItem(id) {
  expandTree(true);
  tree = document.getElementById("criteriaTree");
  for(i = 0; i < tree.view.rowCount; ++i) {
    currentId = tree.view.getItemAtIndex(i).firstChild.firstChild.getAttribute("id");
    if (currentId == id) {
      tree.view.selection.select(i);
      if (document.getElementById("tabBox").selectedIndex != 1) tree.treeBoxObject.scrollToRow(i);
      break;
    }
  }
}


// Expands or collapses the tree
// bool: "false" to collapse, "true" to expand
function expandTree(bool) {
  var treeitems = document.getElementsByTagName("treeitem");
  for (var i = 0; i < treeitems.length ; i++) {
    var children = treeitems[i].getElementsByTagName("treeitem");
    if (children.length > 0) treeitems[i].setAttribute("open", bool);
  }
}


// Triggered when a new criterion is selected in the tree
// Fills criteria's fields with new values
function treeselect(tree) {
  //Forces focus to trigger possible onchange event on another XUL element
  document.getElementById("criteriaTree").focus();
  if (tree.currentIndex != -1) {
    id = tree.view.getItemAtIndex(tree.currentIndex).firstChild.firstChild.getAttribute("id");

    document.getElementById("f-c-desc0").setAttribute("label", "0: "+myDoc.getkeydesc0(id));
    document.getElementById("f-c-desc1").setAttribute("label", "1: "+myDoc.getkeydesc1(id));
    document.getElementById("f-c-desc2").setAttribute("label", "2: "+myDoc.getkeydesc2(id));
    var score = myDoc.getkeyscore(id);

    document.getElementById("f-c-desc").value = myDoc.getkeydesc(id);
    if (score == "-1") {
      document.getElementById("f-g-score").hidden = "true";
      freezeScore("true");
    } else {
      document.getElementById("f-g-score").hidden = "";
      document.getElementById("f-c-score").selectedIndex = score;
      freezeScore("");
    }

    document.getElementById("f-c-comments").value = myDoc.getkeycomment(id);
    freezeComments("");

    if (myDoc.hassubelements(id)) {
      drawChart(id);
    } 
    else {
      var parentId = myDoc.getparent(id);
      if (parentId) drawChart(parentId);
    }
  }
}


// Triggered when current criteria's comments are modified
function changeComments(xulelement) {
  myDoc.setkeycomment(id, xulelement.value);
  docHasChanged();
}


// Triggered when current criteria's score is modified
function changeScore(score) {
  myDoc.setkeyscore(id, score);
  docHasChanged();
}
