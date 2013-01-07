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
** O3S
** commons.js: common JavaScript functions
**
*/

function matchStart(target, pattern) {
  var pos = target.indexOf(pattern);
  if (pos == 0) {
    return true;
  } else {
    return false;
  }
}

function expand(div) {
  var rows = document.getElementsByTagName("tr");
  var id = div.parentNode.parentNode.id + "-";
  for (var i = 0; i < rows.length; i++) {
    var r = rows[i];
    if (matchStart(r.id, id)) {
      if (document.all) r.style.display = "block"; //IE4+ specific code
          else r.style.display = "table-row"; //Netscape and Mozilla
    }
  }
  div.className = "expanded";
  div.onclick = function () {
    collapse(this);
  }
}

function collapse(div) {
  var rows = document.getElementsByTagName("tr");
  var id = div.parentNode.parentNode.id + "-";
  for (var i = 0; i < rows.length; i++) {
    var r = rows[i];
    if (matchStart(r.id, id)) {
      r.style.display = "none";
    }
  }
  div.className = "collapsed";
  div.onclick = function () {
    expand(this);
  }
}

function collapseAll() {
  var rows = document.getElementsByTagName("TR");
  for (var j = 0; j < rows.length; j++) {
    var r = rows[j];
    if (r.id.indexOf("-") >= 0) {
      r.style.display = "none";
      /*var div = r.childNodes[1].childNodes[1];
      div.className = "collapsed";
      div.onclick = function () {
        expand(this);
      }*/
    }
  }
  document.getElementById("all_selector").href = "javascript:expandAll();";
  document.getElementById("all_selector").firstChild.nodeValue = "Expand All";
}

function expandAll() {
  var rows = document.getElementsByTagName("TR");
  for (var j = 0; j < rows.length; j++) {
    var r = rows[j];
    if (r.id.indexOf("-") >= 0) {
      if (document.all) r.style.display = "block"; //IE4+ specific code
          else r.style.display = "table-row"; //Netscape and Mozilla
      /*var div = r.childNodes[1].childNodes[1];
      div.className = "expanded";
      div.onclick = function () {
        collapse(this);
      }*/
    }
  }
  document.getElementById("all_selector").href = "javascript:collapseAll();";
  document.getElementById("all_selector").firstChild.nodeValue = "Collapse All";
}