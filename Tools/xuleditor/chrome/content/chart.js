/*
 **  Copyright (C) 2006-2011 Atos
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
 **  chart.js: functions associated with the chart tab
 **
 */

const SCALE = 100; //1 QSOS unit in pixels
const FONT_SIZE = SCALE/10;

//Clear the SVG chart
function clearChart() {
  var myChart = document.getElementById("chart");
  while (myChart.firstChild) {
    myChart.removeChild(myChart.firstChild);
  }
}

//Draw the SVG chart of a criterion
//criterion: if not specified, the top-level chart of sections is displayed
function drawChart(name) {
  clearChart();
  var myChart = document.getElementById("chart");
  //var width = myChart.parentNode.width.animVal.value / 2;
  //var height = myChart.parentNode.height.animVal.value / 2;
  var width = 400;
  var height = 250;
  myChart.setAttribute("transform", "translate("+width+","+height+")");

  //Collect charting data
  var myScores = (name)?myDoc.getSubChartData(name):myDoc.getChartData();

  //Chart's label
  clearLabels();
  var marker = null;

  if (name) marker = addLabel(name, null);
  var parentName = myDoc.getChartDataParent(name);

  while (parentName != null) {
    marker = addLabel(parentName, marker);
    parentName = myDoc.getChartDataParent(parentName);
  }
  addFirstLabel(marker);

  //draw chart's axis
  drawAxis(myScores.length);

  //draw path between points on each axis
  var myPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
  var myD = "";
  var angle;
  for (i=0; i < myScores.length; i++) {
    myD += (i==0)?"M":"L";
    angle = (i+1)*2*Math.PI/(myScores.length);
    myD += " " + (myScores[i].score)*SCALE*Math.cos(angle) + " " + (myScores[i].score)*SCALE*Math.sin(angle) + " ";
    //2.1 = 2 + 0.1 of padding before actual text display
    drawText(2.1*SCALE*Math.cos(angle), 2.1*SCALE*Math.sin(angle), myScores[i]);
  }
  myD += "z";

  myPath.setAttribute("d", myD);
  myPath.setAttribute("fill", "none");
  myPath.setAttribute("stroke", "red");
  myPath.setAttribute("stroke-width", "2");

  myChart.appendChild(myPath);
}

//Add the root label of the chart navigation bar
//marker: label before which the new label is to be inserted, can be null
function addFirstLabel(marker) {
  var label = document.getElementById("chart-label");
  var newLabel = document.createElement("label");
  newLabel.setAttribute("value", myDoc.getappname() + " " + myDoc.getrelease());
  newLabel.setAttribute("onclick", "drawChart()");
  newLabel.style.cursor = "pointer";

  if (marker) {
    label.insertBefore(newLabel, marker);
  } else {
    label.appendChild(newLabel);
  }

  return newLabel;
}

//Add a label to the chart navigation bar
function addLabel(name, marker) {
  var label = document.getElementById("chart-label");
  var newLabel = document.createElement("label");
  newLabel.setAttribute("value", ">  " + myDoc.getkeytitle(name));
  newLabel.setAttribute("onclick", "selectItem(\"" + name + "\"); drawChart(\"" + name + "\")");
  newLabel.style.cursor = "pointer";

  if (marker) {
    label.insertBefore(newLabel, marker);
  } else {
    label.appendChild(newLabel);
  }

  return newLabel;
}

//Clear all labels
function clearLabels() {
  var label = document.getElementById("chart-label");
  while (label.firstChild) {
    label.removeChild(label.firstChild);
  }
}

//draw "n" equidistant axis
function drawAxis(n) {
  drawCircle(0.5*SCALE);
  drawCircle(SCALE);
  drawCircle(1.5*SCALE);
  drawCircle(2*SCALE);

  for (i=1; i < n+1; i++) {
    drawSingleAxis(2*i*Math.PI/n);
  }
}

//draw a single axis at "angle" (in radians) from angle 0
function drawSingleAxis(angle) {
  x2 = 2*SCALE*Math.cos(angle);
  y2 = 2*SCALE*Math.sin(angle);
  drawLine(0, 0, x2, y2);
}

//draw a circle of "r" radius
function drawCircle(r) {
  var myChart = document.getElementById("chart");

  var myCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
  myCircle.setAttribute("cx", 0);
  myCircle.setAttribute("cy", 0);
  myCircle.setAttribute("r", r);
  myCircle.setAttribute("fill", "none");
  myCircle.setAttribute("stroke", "blue");
  myCircle.setAttribute("stroke-width", "1");

  myChart.appendChild(myCircle);
}

//draw a line between two points
function drawLine(x1, y1, x2, y2) {
  var myChart = document.getElementById("chart");

  var myLine = document.createElementNS("http://www.w3.org/2000/svg", "line");
  myLine.setAttribute("x1", x1);
  myLine.setAttribute("y1", y1);
  myLine.setAttribute("x2", x2);
  myLine.setAttribute("y2", y2);
  myLine.setAttribute("stroke", "green");
  myLine.setAttribute("stroke-width", "1");

  myChart.appendChild(myLine);
}

//draw an axis legend
//x, y: coordinates
//myScore: object chartData (cf. Document.js)
function drawText(x, y, myScore) {
  var myChart = document.getElementById("chart");

  var myText = document.createElementNS("http://www.w3.org/2000/svg", "text");
  myText.setAttribute("x", x);
  myText.setAttribute("y", y);
  myText.setAttribute("font-family", "Verdana");
  myText.setAttribute("font-size", FONT_SIZE);

  if (myScore.score) {
    myText.setAttribute("fill", "green");
  } else {
    myText.setAttribute("fill", "red");
  }

  if (myScore.children) {
    myText.setAttribute("onclick", "selectItem(\"" + myScore.name + "\"); drawChart(\"" + myScore.name + "\")");
  } else {
    myText.setAttribute("onclick", "selectItem(\"" + myScore.name + "\"); document.getElementById('tabs').selectedIndex = 2");
  }
  myText.style.cursor = "pointer";

  myText.appendChild(document.createTextNode(myScore.title));
  myChart.appendChild(myText);

  //text position is ajusted to be outside the circle shape
  myTextLength = myText.getComputedTextLength();
  myX = (Math.abs(x)==x)?x:x-myTextLength;
  myY = (Math.abs(y)==y)?y+FONT_SIZE:y;
  myText.setAttribute("x", myX);
  myText.setAttribute("y", myY);
}