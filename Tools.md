---
layout: page
title: "Tools"
description: "QSOS tools"
tagline: Use!
group: navigation
permalink: /tools
---
{% include JB/setup %}

![QSOS logo](https://raw.github.com/drakkr/QSOS/master/Docs/fr/Images/QSOS-small.png){: .center-image }

<a href="https://github.com/drakkr/QSOS/"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Fork me on GitHub"></a>

### Detailed tutorial

QSOS provides a detailed tutorial that you can:

* [Consult](http://edoc.qsos.org) online in the QSOS edoc repository

* Download in PDF export format: [English](http://dist.qsos.org/qsos-tutorial-2.0_en.pdf), [French](http://dist.qsos.org/qsos-tutorial-2.0_fr.pdf)

### Brief overview

![QSOS tools](https://raw.githubusercontent.com/drakkr/QSOS/master/Docs/en/Images/tools.png){: .center-image }

QSOS templates and evaluations are formatted in XML. The following tools are available to manipulate these XML documents.

* __Freemind__: the famous minmapping tool is used to design evaluation templates

* __QSOS Editor__: to evaluate an open source solution as soon as a template is available

* __QSOS Repositories and Backend__: a Web Git-based application to host, manage and share both templates and evaluations

* __O3S (Open Source Selection Software)__: a Web application to manipulate evaluations, create comparisons and export them in several formats

### Freemind for _templates_

![Template in Freemind](https://raw.githubusercontent.com/drakkr/QSOS/master/Docs/fr/Images/template-name_fr.png){: .center-image }

Since [Freemind](http://freemind.sourceforge.net/) file format is XML based and it wonderfully helps designing trees, QSOS adopted it to create its own templates. In order to be compliant with QSOS evaluations, this `.mm` format is overloaded just a little bit.

Check the [QSOS tutorial](http://dist.qsos.org/qsos-tutorial-2.0_en.pdf) for more details.

### QSOS Editor for _evaluations_

![QSOS Editor](https://raw.githubusercontent.com/drakkr/QSOS/master/Docs/fr/Images/xuleditor-tab-criteria_fr.png){: .center-image }

Although you can edit `.qsos` XML files with any text editor, QSOS provides a specific tool to assist the evaluation and scoring processes. QSOS Editor also ensure separation of concerns between templates and evaluations based on them. Meaning you can't alter the template of an evaluation if you use this tool.

QSOS Editor comes in two flavors:

* A Firefox [extension](http://dist.qsos.org/xuleditor-firefox-2.0.xpi): [legacy](https://support.mozilla.org/en-US/kb/frequently-asked-questions-firefox-addon) since Firefox 57 :(

* A standalone [application](http://dist.qsos.org/xuleditor-application-2.0.zip): follow [install guide](http://dist.qsos.org/qsos-install-2.0_en.pdf) to run it on top of Mozilla xulrunner

Furthermore this tool is connected to the QSOS backend and thus allows transparent bootstrap, download, update and upload of evaluations. 

Check the [QSOS tutorial](http://dist.qsos.org/qsos-tutorial-2.0_en.pdf) for more details.

### QSOS Backend for both

![QSOS backend](https://raw.githubusercontent.com/drakkr/QSOS/master/Docs/fr/Images/o3s-timeline_fr.png){: .center-image }

The backend manages the two QSOS repositories:

* _Master_: templates and evaluations formally validated by the QSOS community

* _Incoming_: templates and evaluations contributed by anybody

Everybody can send a template or an evaluation into the _Incoming_ repository, wheter trough the backend's Web interface or directly  via the QSOS Editor.

It is available here: <http://backend.qsos.org>.

Only allowed members of the QSOS community can then move templates and evaluations from the _Incoming_ repository to the _Master_ repository.

Check the [QSOS tutorial](http://dist.qsos.org/qsos-tutorial-2.0_en.pdf) for more details.

### O3S for _comparisons_ 

![O3S](https://raw.githubusercontent.com/drakkr/QSOS/master/Docs/fr/Images/o3s-comparison-table_fr.png){: .center-image }

The _Open Source Selection Software_ connects on both QSOS repositories to allow creation of personalized comparisons:

* Official instance: <http://master.o3s.qsos.org>

* Sandbox instance: <http://incoming.o3s.qsos.org>

Once a comparison has been created it can be exported in several formats:

* Graphics: QSOS quadrants (Maturity versus Fonctional coverage), radar graphs

* Documents: 

    * Dynamic comparative spreadsheets (`.ods`)

    * Reports skeletons (`.odt`)

    * Slideshows skeletons (`.odp`)

Check the [QSOS tutorial](http://dist.qsos.org/qsos-tutorial-2.0_en.pdf) for more details.