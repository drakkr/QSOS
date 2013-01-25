#  Copyright (C) 2006-2013 Atos
#
#  This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
#
#
# QSOS XUL Editor Makefile
# $Id: Makefile,v 1.14 2010/12/23 17:54:25 rsemeteys Exp $

APPPACK=application.ini Changes chrome.manifest LICENSE README xuleditor xuleditor.bat

EXTPACK=Changes chrome.manifest install.rdf LICENSE README

APPSRC=chrome/content/chart.js chrome/content/confirmUpload.xul chrome/content/criteria.js chrome/content/editor.js chrome/content/file.js chrome/content/load.js chrome/content/logo32.png chrome/content/logo.svg chrome/content/new.xul chrome/content/chrome/content/qsos-overlay.xul chrome/content/compatibility.js chrome/content/confirm.xul chrome/content/Document.js chrome/content/editor.xul chrome/content/general.js chrome/content/load.xul chrome/content/logo-qsos.png chrome/content/new.js chrome/content/qsos-overlay.js

EXTSRC=$(APPSRC) chrome/content/qsos-overlay.js chrome/content/qsos-overlay.xul

LOCALE=chrome/locale/en-US/confirm.dtd chrome/locale/en-US/confirmUpload.dtd chrome/locale/en-US/editor.dtd chrome/locale/en-US/load.dtd chrome/locale/en-US/new.dtd chrome/locale/en-US/editor.properties chrome/locale/en-US/qsos-overlay.dtd chrome/locale/fr-FR/confirm.dtd chrome/locale/fr-FR/confirmUpload.dtd chrome/locale/fr-FR/editor.dtd chrome/locale/fr-FR/load.dtd chrome/locale/fr-FR/new.dtd chrome/locale/fr-FR/editor.properties chrome/locale/fr-FR/qsos-overlay.dtd

SKIN=chrome/skin/classic.css chrome/skin/document-open_32.png chrome/skin/documentation.png chrome/skin/document-edit.png chrome/skin/office-chart-pie.png chrome/skin/help-about.png chrome/skin/quickopen-file.png chrome/skin/preferences-web-browser-shortcuts.png chrome/skin/document-open.png chrome/skin/document-open-remote.png chrome/skin/document-save.png chrome/skin/document-save-as.png chrome/skin/folder-sync.png chrome/skin/folder-downloads.png chrome/skin/download.png chrome/skin/document-close.png chrome/skin/application-exit.png chrome/skin/list-add.png chrome/skin/list-remove.png

PREFS=defaults/preferences/qsos-xuled.js

PREFS_XULRUNNER=defaults/preferences/qsos-xuled-xulrunner.js

VERSION=2.0

ext:
	rm -f xuleditor-firefox-${VERSION}.xpi
	zip -r xuleditor $(EXTPACK) $(EXTSRC) $(LOCALE) $(SKIN) $(PREFS)
	mv xuleditor.zip xuleditor-firefox-${VERSION}.xpi

app:
	rm -f xuleditor-application-${VERSION}.zip
	zip -r xuleditor-application-${VERSION}.zip $(APPPACK) $(APPSRC) $(LOCALE) $(SKIN) $(PREFS_XULRUNNER)
