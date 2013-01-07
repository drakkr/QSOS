/*
**  Copyright (C) 2006-2009 Atos
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
** QSOS XUL Editor
** Listener to trap URI modification and .qsos file browsint to redirect to XUL Editor
** qsos-overlay.js: functions associated with the qsos-overlay.xul file
**
*/


function registerMyListener() {
	window.getBrowser().addProgressListener(myListener, Components.interfaces.nsIWebProgressListener.STATE_START);
}

function unregisterMyListener() {
	window.getBrowser().removeProgressListener(myListener);
}

window.addEventListener("load", registerMyListener, false);
window.addEventListener("unload", unregisterMyListener, false);

var myListener = {
	QueryInterface:function(a){},
	onStateChange:function(a,b,c,d){},
	onLocationChange:function(aProgress,aRequest,aURI) {
		var url = aURI.spec;
		url = url.split('?')[0];
		if (url.substr(-5) == ".qsos") {
		  if (window.confirm("Open "+url+" with QSOS XUL Editor?")) {
		    window.openDialog('chrome://qsos-xuled/content/editor.xul','test', '_blank', 'chrome,dialog=no', url);
		  }
		}
	},
	onProgressChange:function(a,b,c,d,e,f){},
	onStatusChange:function(a,b,c,d){},
	onSecurityChange:function(a,b,c){}
}