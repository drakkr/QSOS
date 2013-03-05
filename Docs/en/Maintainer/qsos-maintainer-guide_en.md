% QSOS Maintainer Guide
% ![Logo](../../../Method/en/Images/QSOS.png)
% Version 2.0 - 05/03/2013

# License

Copyright © 2013 Atos.

Permission is granted to copy, distribute and/or modify this document under the terms the GNU Free Documentation License v1.2 published by the Free Software Foundation with no Invariant section, no Front-Cover Texts, and no Back-Cover Texts.

A copy of the license is available at <http://www.gnu.org/copyleft/fdl.html>.

# Changelog

--------------------------------------------------------------
 Version   Date       Authors           Comments
--------- ----------  ----------------- ----------------------
  2.0      05/03/13   Raphaël Semeteys  Initial version.
--------------------------------------- ----------------------

# Introduction

This document aims to give directions and guidance regrading maintenance of the QSOS project itself. We do not consider here maintenance of data components like QSOS templates or evaluation which are managed in the dedicated Git repositories _QSOS-Master.git_ and _QSOS-Incoming.git_. 

Components discussed here are called core components and are managed in the _QSOS.git_: 

* Documentation related to the QSOS project itself: method, tutorial, roadmap...

* QSOS formats and related XML documents like XSD schemas, XSL transformations...

* Tools developped to help use the QSOS method, manipulate its formats and manage data components.

# Version naming convention

Versions of QSOS core components (documents, formats and tools) follow the X.Y pattern:

* X is a number representing a major version.

* Y is a number representing a minor subversion of the major version.

Majors versions are generally set by the QSOS format itself (`.qsos` file format) since major changes on this format imply modification of almost every other component of the project.

So the rule is that when the QSOS format changes its major version, every other component should also change to this new major version.

Impacts of a minor version is generally restricted to a specific component. Therefore QSOS components don't necesseraly share minor versions.

# The QSOS.git repository

As explained before, QSOS.git should not be mistaking with repositories for QSOS data components. Its purpose is to store and manage maintenance of QSOS core components.

The QSOS.git repository is structured like this:

* Docs: folder for QSOS documentation, split by language.

* Method: folder containing the QSOS method itself, split by language.

* Tools: folder for QSOS tools and format.

TODO: Give links to actual repository (on GitHub ?)

# Modification of QSOS core components

## Modification of QSOS edocs

In QSOS, documents are considered like source code. They are coded in Markdown^[<http://daringfireball.net/projects/markdown/> with Pandoc extensions] syntax, exported by Pandoc^[<http://johnmacfarlane.net/pandoc/>] in several formats and built by Make^[<http://www.gnu.org/software/make/>]. 

The source documents are called _edocs_ (because _edoc_ is juste like _code_, when you read from right to left).

Edocs can be created or modified with any text editor. If the edoc length is big, it is recommended to split it in several `.md` files, on per section or first-level chapter.

An edoc generally has a your-edoc-_head__en .md file, containing the edoc's header (title, authors, version), license note and changelog that is used for PDF export only.

For instance _qsos-maintainer-guide-head_en.md_:

~~~{.Mandoc .numberLines}
% QSOS Maintainer Guide
% ![Logo](Images/QSOS.png)
% Version 2.0 - 18/02/2013

# License

Copyright © 2013 Atos.

Permission is granted to copy, distribute and/or modify this document [...]

A copy of the license is available at <http://www.gnu.org/copyleft/fdl.html>.

# Changelog

-----------------------------------------------------------
  Version   Date       Authors           Comments
--------- ----------  ----------------- -------------------
  2.0      18/02/13   Raphaël Semeteys  Initial version.
--------------------------------------- -------------------
~~~
    
In the QSOS.git repository, images are stored in the a subfolder _Images_ placed in the same folder as the edoc.

When you add new sections or chapters to an edoc you have to modify the _Makefile_ file accordingly. This file is a input for the `Make` build system, in charge of producing PDF and Gitit exports. 

Let's look at the QSOS method's makefile _Method/en/Makefile_.

~~~{.Makefile .numberLines}
# Makefile for QSOS documentation
DOC=qsos
VERSION=2.0
LANG=en
TITLE=Qualification and Selection of Opensource Software (QSOS)

#List of edocs to be included in Gitit export
PAGES=qsos-intro_$(LANG).md qsos-process_$(LANG).md qsos-step1_$(LANG).md
 qsos-step2_$(LANG).md qsos-step3_$(LANG).md qsos-step4_$(LANG).md
 qsos-community_$(LANG).md qsos-appendixA_$(LANG).md

#List of edocs to be included in other exports
CHAPTERS=qsos-head_$(LANG).md qsos-manifesto_$(LANG).md
 qsos-changelog_$(LANG).md  $(PAGES) qsos-appendixB_$(LANG).md

DOCNAME=$(DOC)-$(VERSION)_$(LANG)

doc-pdf:
	mkdir -p dist
	pandoc -N --toc --template=qsos-template_$(LANG).latex 
 dist/$(DOCNAME).pdf $(CHAPTERS)

gitit:
	mkdir -p dist
	cp -R Images dist/.
	#Create summary Gitit page
	echo '---' > dist/$(DOCNAME).page
	echo 'title: $(TITLE)' >> dist/$(DOCNAME).page
	echo '...' >> dist/$(DOCNAME).page
	echo -e '\n' >> dist/$(DOCNAME).page

	$(foreach PAGE, $(PAGES), 
	  echo -e "[$(subst # ,,$(shell head -1 $(PAGE)))]($(basename $(PAGE))) \n" 
	    >> dist/$(DOCNAME).page 
	;)

	#Create Gitit pages with titles
	$(foreach PAGE, $(PAGES), 
	  echo -e "---\ntitle: $(subst # ,,$(shell head -1 $(PAGE)))\n...\n" 
	    > dist/$(basename $(PAGE)).page ; 
	  cat $(PAGE) >> dist/$(basename $(PAGE)).page 
	;)

clean:
	rm -Rf dist/
~~~

Here are some noticeable points:

* You need to change the _VERSION_ parameter when you package a new version of the edoc and exports.

* The _PAGES_ parameter lists the Markdown files to be included in the Gitit export, as wiki pages.

* The _CHAPTERS_ parameter lists the Markdown files to be exported as a whole in a PDF document. Here, a specific head for PDF export but also the QSOS Manisfesto, the edoc's changelog and a second appendix are added to the _PAGES_ list. Therfore they won't be included in the Gitit export.

* The _doc-pdf_ target, aimed through `make` (because it is the first target) or `make doc-pdf`, builds the PDF export from the _CHAPTERS_ list (through LaTeX and the use of the  _qsos-template_en.latex_ template^[You can modify this template if you want to fine tune or personalize the PDF export at the LaTeX level.]).

* The _gitit_ target uses the _PAGES_ list to build Gitit wiki pages:

    + A menu page (here: _qsos-2.0_en.page_) containing links to the other pages.

    + A page per Markdown file with the page's title based on the first line of the Markdown file.

* PDF file and Gitit pages are exported into the _dist/_ folder for further use.

* The _clean_ target deletes the temporary _dist/_ folder.

All makefiles of QSOS edocs follow that kind of structure and build process. 

QSOS edocs and generated export documents are distributed under the terms of the _GNU Free Documentation License_^[<http://www.gnu.org/copyleft/fdl.html>].

## Modification of QSOS tools

All QSOS tools are distributed under the terms of the _GNU General Public License_^[<http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>] version 2.

### Modification of O3S

O3S (Open Source Selection Sofware) source code is stored in the _Tools/o3s_ folder of the QSOS.git repository.

![O3S global architecture](Images/o3S-architecture.png)

O3S is composed of several components:

* Git repositories: for storage and version management of QSOS templates and evaluations.

* QSOS Backend: Web application in charge of the Git repositories management and synchronisation with a central database.

* O3S Frontends: Web applications implementing the _Qualify_ and _Select_ of the QSOS method, based on the QSOS backend.

#### Git repositories and QSOS Backend

Git has been selected to store and manage version of QSOS templates and evaluations. For more convinience the MySQL database of the QSOS Backend also contains some metadata regarding theses templates and evaluations. Therefore both Git repositories and central database need to always be synchronized.

To ensure that coherence, one never directly pushes in the Git repositories. Templates and evaluations __must__ be added through the QSOS Backend which pushes in the Git repositories as well as writes into the central database.

The Git repositories are split in two:

* QSOS-Incoming: repository for contributions from users with an account on the QSOS Backend. Anybody can create an account and then contribute to this repository. They only limitations imposed by the QSOS Backend are the following ones:

    + Contributions must be proper QSOS evaluations or templates, compliance with XSD schemas is verified.

    + Only the original contributor of an evaluation can overwrite it. If another user tries to contribue an evaluation on the same software and the same version, he will be given an error message with contact information of the original contributor.

* QSOS-Master: repository for contrbutions validated by the QSOS project. Only users with the _moderator_ status can upgrade a template of an evaluation from QSOS-Incoming into QSOS-Master when he estimates that its quality and objectivity are sufficient enough.

TODO: Links to the QSOS-Master.git and QSOS-Incoming.git repositories.

The QSOS Backend is a Web application, stored in the QSOS.git repository in the _Tools/o3s/backend/app_ folder. The database schema, qhared with O3S Frontend, is stored in _Tools/o3s/create_db.sql_. 

This application is built with the following oepn source technologies:

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

This application is built with the following oepn source technologies:

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

An O3S Frontend doesn't kwnow about Git and only accesses in read mode to the database and the backend's filesystem where QSOS templates and evaluations are stored by Git.

The two application also have separated configuration files, allowing multiple deployment scenarios.

You might sill wonder why there is not only one unique Web application regrouping both backend and frontend and using the same technologies. Well, you can call the actual situation both architecture design, legacy and convergence of two different development teams!

### Modification of XulEditor

#### Coding XulEditor

XulEditor is the tool used to create and modify QSOS evaluations. It can also be used to contribute QSOS evaluations to the QSOS-Incoming.git repository, through the QSOS Backend.

XulEditor is named after the main technology used for its GUI: XUL, from the Mozilla project. The Mozilla technological platform was chosen for the following reasons:

* Portability: Mozilla technologies are ported on evry main operating systems.

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

* Changes: do not forget to log the changes of the  new version.

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

* The _LOCALE_ ans _SKIN_ parameters list files related to internationalisation and GUI decoration.

* For the standalone application:

    + The _APPPACK_ parameter lists files linked to the standalone application packaging.

    + The _APPSRC_ parameter lists source files (XUL and JavaScript) composing the application.

    + The _PREFS_XULRUNNER_ parameter define the prefernce file specific to the application.

    + The _app_ target builds the application based on preceeding parameters, and generates a `.zip` files

* For the standalone extension:

    + The _EXTPACK_ parameter lists files linked to the standalone application packaging.

    + The _EXTSRC_ parameter lists source files (XUL and JavaScript) composing the application.

    + The _PREFS_ parameter define the prefernce file specific to the application.

    + The _ext_ target builds the application based on preceeding parameters, and generates a `.ini` files

#### Releasing Xuleditor

Once XulEditor packages are generated you still need to actually release them to the QSOS community using the Mozilla update system. To do so, you need to fulfill Mozilla security requirement end therefore to sign your extention.

The XulEditor Firefox extension is signed with help of the McCoy^[<https://developer.mozilla.org/en/docs/McCoy>] tool. You'll need the _xuleditor@qsos.org_ RSA key to do so. If you don't have it... well you should question yourself about being a QSOS maintainer!

![Use of McCoy](Images/McCoy.png)

When you're done with McCoy and your actual role in the QSOS project, evrything is ready for your new version to be released: you just signed _xuleditor-update.rdf_ in the name of the QSOS community. 

Push the file into the Git repository and also to the QSOS website. Users of old XulEditor versions will now be notified of the new one.

# Impact analysis of some modifications

## Modification of the QSOS Maturity axis

When you modify the Maturity section, it has impacts on several other QSOS components:

* the _criteria-maturity_[language].xml_ reference files: it is probably in one of them where you'll make your initial modifications

* the method itself: part of the qsos-appendic4-[language] can be generated by applying the _/Tools/o3s/formats/xml/xslt/evaluation-template-markdown.xsl_ transformation to previous files

* other XSLT files in _/Tools/o3s/formats/xml/xslt/_

* the _/Tools/xuleditor/chrome/content/compatibility.js_ file, by using the `sed 's/"/\\"/g' <file.xslt> | sed 's/$/\\/g` command to transform XSLT files to strings compatible with JavaScript

* the template bootstraps in _/Tools/o3s/formats/xml/template_

Depending on the nature of your modificaions, you also might have to modify other QSOS documents like the tutotial or this very maintainer guide.

## Modification of the QSOS format

If you modify the `.qsos` format, it also has impacts on several other QSOS components:

* files in _/Tools/o3s/formats_: 

     + XML files: XSL transformations, XSD, template bootstraps

     + PHP libraries wrapped around the XML files 

* the method itself

* the QSOS tools: XulEditor, O3S

# Translation and localization

QSOS has been designed as a international project. Therefore everything is in place to ease its translation in a new language.

This section will guide you through the different components to be translated and the related techniques. Since a good example is often better than a lot of theory, let's consider translateing QSOS in English.

## Prerequisites

You need to determine what ISO 639-1^[<http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes>] language code to use. In our example it is _en_.

For some translation techniques you'll also need the ISO 3166-1 country code^[<http://en.wikipedia.org/wiki/ISO_3166-1>]. In our exemple let's use _US_, which gives us a full localization name of _en-US_.

These codes will be used to identify the English translation files.

## Translation of the QSOS method

The source files of the QSOS method are stored in the QSOS.git repository, in the _Method_ folder. 

Here is how to proceed to create a new translation: 

* Create a new subfolder with the language code name (here _en_) and copy the content of an existing translation.

* Rename all files to integrate the language code name (i.e. _Method/fr/qsos-head_fr.md_ becomes _Method/en/qsos-head_en.md_ and so on).

* Translate the `.svg` files in _Images_ and export them in `.png` files (by using Inkscape^[<http://www.inkscape.org>] for instance).

* Translate the XML file describing the QSOS Maturity section (here _criteria-maturity_en.xml_)

* Translate contents  of the `.md` files, do not forget to reference your translated images. You can apply the _/Tools/o3s/formats/xml/xslt/evaluation-template-markdown.xsl_ transformation to the pevious XML file to generate part of the _qsos-appendixA_en.md_ file.

* Tanslate the LaTeX template (here _qsos-template_en.latex_): you probably only need to translate line 42.

* Adapt the _Makefile_  file to reference your translated files, you probably only need to modify the _LANG_ and _TITLE_ parameters.

You should now be able to export your translated version of the method in PDF and Gitit formats with `make`.

## Translation of other QSOS documents

Proceed as explained above for the Method to translate other QSOS documents, for instance:

* QSOS Tutorial: in _Docs/_en_/Tutorial_ folder.

* This Maintainer Guide: in _Docs/_en_/Maintainer_ folder.

## Translation of QSOS website

QSOS Website is based on the Drakkr^[Visit <http://www.drakkr.org> for further details] project Web infrastructure:

* Dynamic pages: based on the open source Content Management System _WordPress_^[<http://wordpress.org>].

* Static pages: based on the open source Wiki _Gitit_^[<http://gitit.net>].

### Dynamic pages (WordPress)

TODO: complete when new WordPress infrastructure is defined and deployed.

### Static pages (Gitit)

Drakkr's Gitit is deployed with git as a backend for the pages.

TODO: complete with repo structure and description on how to export edocs as pages.

## Translation of QSOS reference template

### Template bootstraps

The bootstraps to create new QSOS templates are stored in the QSOS.git repository, in the _Tools/o3s/formats/xml/template_ folder. Duplicated an existinf `.mm` file and rename it appopriatly (here _template_en.mm_).

Use FreeMind^[<http://freemind.sourceforge.net>] to open and translate your file:

* Reuse the QSOS Maturity section (here _criteria-maturity_en.xml_) to translate the _Maturity_ node. 

* Translate the other nodes, __except__ the _Metadata_ subnodes: _version_, _language_, _authors_, _author_, _name_, _email_, _dates_, _creation_, _update_.

* Do not forget to put your language code name (here _en_) in the node under the _language_ node.

### Template to evaluation XSLT

In the QSOS.git repository, in the _Tools/o3s/formats/xml/xslt_ folder, the Template to Evaluation XSLT must be translated (here _template-to-evaluation_en.xsl_): copy the _Maturity_ `<section/>` from _criteria-maturity_en.xml_.

## Translation of QSOS tools

### Translation of O3S

Here are the files you have to translate in O3S:

* For the O3S frontend (_Tools/o3s/app/_ folder):

    - _locales/en.php_: translate the `$msg` array.

    - _config.php_: set the `$default_lang` to your locale and add it to the `$supported_lang` array (for instance `array('fr', 'en')`).

* For the 03S backend (_Tools/o3s/backend/app_ folder):

    - _lang/en.php_: translate the `TXT_*` constants.

    - _conf.php_: add a`elseif (strstr($lang[0], 'en'))` instruction block to point to the translation file (here `lang/en.php`).

### Translation of XulEditor

* For the XulEditor GUI (_Tools/xuleditor/chrome/locale_ folder):

    - Create a new folder for your translation, using its full localization name (here _en-US_).

    - Copy thete contents of an existing translation.

    - In all `*.dtd` files: translate attribute values of `<!ENTITY/>` elements. 

    - Translate all `*.properties` files.

* For the XSL transformations included in XulEditor (_Tools/xuleditor/chrome/content_ folder):

    - In the _compatibility.js_ file: add a _template_to_qsos_2_0_en_ variable containing the result of this command `sed 's/"/\\"/g' <file.xsl> | sed 's/$/\\/g'` where `<file.xsl>` points to the previously translated Template to Evaluation XSLT (here _Tools/o3s/formats/xml/xslt/template-to-evaluation_en.xsl_).

* Update XulEditor packaging and build (_Tools/xuleditor_ folder):

    - In _chrome.manifest_: add a `locale` instruction (here `locale qsos-xuled en-US chrome/locale/en-US/`)

    - In _Makefile_: add paths to your GUI translated files in the _LOCALE_ parameter.

# Appendix: the Drakkr framework

QSOS is part of the Drakkr initiative designed for businesses and organization to deploy an open source governance. Drakkr is a toolkit designed for businesses and organization to deploy an open source governance. It contains recomandations and best practices but also processes and tools.

The Drakkr processes are split in several dedicated projects, the whole providing a comprehensive and coherent framework.

![Drakkr Framework](../../../Method/en/Images/drakkr-schema_en.png)

The Drakkr projects are as follows:

* __OSC__ (Open Source Cartouche): project dedicated to provide a unique identification of an open source component and also related metadata.

* __ECOS__ (Evaluation of Costs linked to Open Source): project focusing on evaluating the Total Cost of Ownership of open source components as long as the Return On Investment of migrations.

* __FLOSC__ (Free/Libre Open Source Complexity): project providing method and tools to evaluate intrisic complexity of open source components.

* __QSOS__ (Qualification and Selection of Opensource Software): project providing method and tools to qualify, select and compare open source components and tus allowing to industrialize and mutualize a process of technical surveillance.

* __SLIC__ (Software LIcense Comparator): project dedicated to formal description of open source licenses and their compatibilities.

* __SecureIT__: project related to the management of security alerts in open source components.

For further details please consult Drakkr website: <http://www.drakkr.org>.
