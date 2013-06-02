## Modification of QSOS tools

All QSOS tools are distributed under the terms of the _GNU General Public License_^[<http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>] version 2.

### Modification of O3S

O3S (Open Source Selection Software) source code is stored in the _Tools/o3s_ folder of the QSOS.git repository.

![O3S global architecture](Images/o3S-architecture.png)

O3S is composed of several components:

* Git repositories: for storage and version management of QSOS templates and evaluations.

* QSOS Backend: Web application in charge of the Git repositories management and synchronization with a central database.

* O3S Frontends: Web applications implementing the _Qualify_ and _Select_ of the QSOS method, based on the QSOS backend.

#### Git repositories and QSOS Backend

Git has been selected to store and manage version of QSOS templates and evaluations. For more convenience the MySQL database of the QSOS Backend also contains some metadata regarding theses templates and evaluations. Therefore both Git repositories and central database need to always be synchronized.

To ensure that coherence, one never directly pushes in the Git repositories. Templates and evaluations __must__ be added through the QSOS Backend which pushes in the Git repositories as well as writes into the central database.

The Git repositories are split in two:

* QSOS-Incoming: repository for contributions from users with an account on the QSOS Backend. Anybody can create an account and then contribute to this repository. The only limitations imposed by the QSOS Backend are the following ones:

    + Contributions must be proper QSOS evaluations or templates, compliance with XSD schemas is verified.

    + Only the original contributor of an evaluation can overwrite it. If another user tries to contribute an evaluation on the same software and the same version, he will be given an error message with contact information of the original contributor.

* QSOS-Master: repository for contributions validated by the QSOS project. Only users with the _moderator_ status can upgrade a template of an evaluation from QSOS-Incoming into QSOS-Master when he estimates that its quality and objectivity are sufficient enough.

TODO: Links to the QSOS-Master.git and QSOS-Incoming.git repositories.

The QSOS Backend is a Web application, stored in the QSOS.git repository in the _Tools/o3s/backend/app_ folder. The database schema, shared with O3S Frontend, is stored in _Tools/o3s/create_db.sql_. 

This application is built with the following open source technologies:

* Server side:

    + The Git distributed revision control system (<http://git-scm.com>)

    + The MySQL relational database (<http://dev.mysql.com>)

    + The PHP server side scripting language (<http://php.net>)

    + The Git.php library (<https://github.com/kbjr/Git.php>)

* Browser side:

    + The Bootstrap Web frontend framework (<http://twitter.github.com/bootstrap/>)

    + The JQuery javascript framework (<http://jquery.com>)

#### O3S Frontends

O3S Frontends are instances of a Web application dedicated to manipulation of QSOS templates, evaluations and comparisons. 

The source code is stored in the QSOS.git repository in the _Tools/o3s/app_ folder.

They are as many instances as there are repositories for templates and evaluation. The repository to which an instance is connected is defined the Frontend _config.php_ file.

This application is built with the following open source technologies:

* Server side:

    + The MySQL relational database (<http://dev.mysql.com>)

    + The PHP server side scripting language (<http://php.net>)

    + The PclZip library (<http://www.phpconcept.net/pclzip/>): used to compress OpenDocument exports

    + The FreeMind mapping application (<http://freemind.sourceforge.net>): used for Mindmap exports

    + The SVG open format (<http://www.w3.org/Graphics/SVG/>): used for graphical exports (Radar charts, QSOS Quadrant)

    + XML related technologies: XSL (XSL Transformation, XPath) and XSD

* Browser side: a few JavaScript developments (<http://www.ecmascript.org>), mainly for basic controls or actions.

#### Link between O3S Frontends and QSOS Backend

We've seen that O3S Frontends and the QSOS Backend share the same database. It is the principal interface between the two Web applications. 

An O3S Frontend doesn't know about Git and only accesses in read mode to the database and the backend's filesystem where QSOS templates and evaluations are stored by Git.

The two application also have separated configuration files, allowing multiple deployment scenarios.

You might sill wonder why there is not only one unique Web application regrouping both backend and frontend and using the same technologies. Well, you can call the actual situation both architecture design, legacy and convergence of two different development teams!

### Modification of XulEditor

#### Coding XulEditor

XulEditor is the tool used to create and modify QSOS evaluations. It can also be used to contribute QSOS evaluations to the QSOS-Incoming.git repository, through the QSOS Backend.

XulEditor is named after the main technology used for its GUI: XUL, from the Mozilla project. The Mozilla technological platform was chosen for the following reasons:

* Portability: Mozilla technologies are ported on every main operating systems.

* Flexibility: XulEditor can be deployed and used whether as a Firefox extension or as a standalone application, with the exact same code base.

* Internationalization: XUL has built-in systems to manage multiple languages and locales.

More precisely the technologies used to develop XulEditor are the following:

* The XUL markup language for the GUI (<https://developer.mozilla.org/en/docs/XUL>): quite similar to XHTML.

* JavaScript for actions (<https://developer.mozilla.org/en-US/docs/JavaScript>): with a few object oriented code in the backend.

Basically it's not very far from some HTML/JavaScript Web developments.

The source code is managed in the QSOS.git repository, in the _Tools/xuleditor_ folder.

#### Packaging and Building Xuleditor

When you intend to release a new version of XulEditor, you need to do some packaging required by the Mozilla technological platform, mainly through the following files:

* application.ini: parameters to launch XulEditor from Firefox or XulRunner^[<https://developer.mozilla.org/en/docs/XULRunner>] (for the standalone application use)

* install.rdf: parameters to install XulEditor on the Mozilla platform.

* Changes: do not forget to log the changes of the new version.

Let's look at the _application.ini_ file:

~~~{.Ini .numberLines}
[App]
  Vendor=Atos
  Name=QSOS XUL Editor
  Version=2.0
  BuildID=20130124
  Copyright=Copyright (c) 2006-2013 Raphael Semeteys, Timothée Ravier
  ID=xuleditor@qsos.org

[Gecko]
  MinVersion=2.0.0
  MaxVersion=18.*

[shell]
  Icon=chrome/icons/

[XRE]
  EnableExtensionManager=1
~~~

Here are minimal modifications you have to make:

* [App] section: increase _Version_ and change _BuildID_

* [Gecko] section: set supported _Minversion_ and _MaxVersion_ of the Mozilla Gecko engine (used by both Firefox and XulRunner)

Regarding the _install.rdf_ file:

~~~{.Xml .numberLines}
<?xml version="1.0"?>
<RDF:RDF xmlns:em="http://www.mozilla.org/2004/em-rdf#"
         xmlns:NC="http://home.netscape.com/NC-rdf#"
         xmlns:RDF="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
  <RDF:Description RDF:about="urn:mozilla:install-manifest"
                   em:id="xuleditor@qsos.org"
                   em:version="2.0"
                   em:type="2"
                   em:name="QSOS XUL Editor"
                   em:description="An editor for QSOS evaluations"
                   em:creator="Raphael Semeteys"
                   em:iconURL="chrome://qsos-xuled/content/logo32.png"
                   em:homepageURL="http://www.qsos.org/"
                   em:aboutURL="chrome://qsos-xuled/content/about.xul"
                   em:updateURL="http://www.qsos.org/tools/xuleditor-update.rdf"
                   em:updateKey="MIGfMA0GCSqGSIb3[...]NYy83"/>
  </RDF:Description>
  <RDF:Description RDF:about="rdf:#$zNYy83"
                   em:id="{ec8030f7-c20a-464f-9b0e-13a3a9e97384}"
                   em:minVersion="2.0"
                   em:maxVersion="18.*" />
</RDF:RDF>
~~~

Here are minimal modifications you have to make:

* Update the _em:version_ attribute.

* Set _em:minVersion_ and _em:maxVersion_ to define supported versions of the Mozilla Gecko engine

XulEditor is built with `Make`. Let's look at the _Tools/xuleditor/Makefile_.

~~~{.Makefile .numberLines}
APPPACK=application.ini [...] xuleditor xuleditor.bat
EXTPACK=Changes chrome.manifest install.rdf LICENSE README
APPSRC=chrome/content/chart.js chrome/content/confirmUpload.xul [...]
EXTSRC=$(APPSRC) chrome/content/qsos-overlay.xul
LOCALE=chrome/locale/en-US/confirm.dtd chrome/locale/fr-FR/editor.properties [...] 
SKIN=chrome/skin/classic.css chrome/skin/document-open_32.png [...]
PREFS=defaults/preferences/qsos-xuled.js
PREFS_XULRUNNER=defaults/preferences/qsos-xuled-xulrunner.js
VERSION=2.0

ext:
	rm -f xuleditor-firefox-${VERSION}.xpi
	zip -r xuleditor $(EXTPACK) $(EXTSRC) $(LOCALE) $(SKIN) $(PREFS)
	mv xuleditor.zip xuleditor-firefox-${VERSION}.xpi

app:
	rm -f xuleditor-application-${VERSION}.zip
	zip -r xuleditor-application-${VERSION}.zip $(APPPACK) $(APPSRC)
               $(LOCALE) $(SKIN) $(PREFS_XULRUNNER)
~~~

Here are some noticeable points:

* You need to change the _VERSION_ parameter when you package a new version of XulEditor.

* The _LOCALE_ ans _SKIN_ parameters list files related to internationalization and GUI decoration.

* For the standalone application:

    + The _APPPACK_ parameter lists files linked to the standalone application packaging.

    + The _APPSRC_ parameter lists source files (XUL and JavaScript) composing the application.

    + The _PREFS_XULRUNNER_ parameter define the preference file specific to the application.

    + The _app_ target builds the application based on preceding parameters, and generates a `.zip` files

* For the standalone extension:

    + The _EXTPACK_ parameter lists files linked to the standalone application packaging.

    + The _EXTSRC_ parameter lists source files (XUL and JavaScript) composing the application.

    + The _PREFS_ parameter define the preference file specific to the application.

    + The _ext_ target builds the application based on preceding parameters, and generates a `.ini` files

#### Releasing Xuleditor

Once XulEditor packages are generated you still need to actually release them to the QSOS community using the Mozilla update system. To do so, you need to fulfill Mozilla security requirement end therefore to sign your extension.

The XulEditor Firefox extension is signed with help of the McCoy^[<https://developer.mozilla.org/en/docs/McCoy>] tool. You'll need the _xuleditor@qsos.org_ RSA key to do so. If you don't have it... well you should question yourself about being a QSOS maintainer!

![Use of McCoy](Images/McCoy.png)

When you're done with McCoy and your actual role in the QSOS project, everything is ready for your new version to be released: you just signed _xuleditor-update.rdf_ in the name of the QSOS community. 

Push the file into the Git repository and also to the QSOS website. Users of old XulEditor versions will now be notified of the new one.
