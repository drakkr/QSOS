# Modification of QSOS core components

## Modification of QSOS edocs

In QSOS, documents are considered like source code. They are coded in Markdown^[<http://daringfireball.net/projects/markdown/> with Pandoc extensions] syntax, exported by Pandoc^[<http://johnmacfarlane.net/pandoc/>] in several formats and built by Make^[<http://www.gnu.org/software/make/>]. 

The source documents are called _edocs_ (because _edoc_ is just like _code_, when you read from right to left).

Edocs can be created or modified with any text editor. If the edoc length is big, it is recommended to split it in several `.md` files, on per section or first-level chapter.

An edoc generally has a your-edoc-_head__en .md file, containing the edoc's header (title, authors, version), license note and changelog that is used for PDF export only.

For instance _qsos-maintainer-guide-head_en.md_:

~~~{.Mandoc .numberLines}
% QSOS Maintainer Guide
% ![Logo](../Images/QSOS.png)
% Version 2.0 - 2013-02-18

# License

Copyright © 2013 Atos.

Permission is granted to copy, distribute and/or modify this document [...]

A copy of the license is available at <http://www.gnu.org/copyleft/fdl.html>.

# Changelog

-----------------------------------------------------------
  Version   Date       Authors           Comments
--------- ----------  ----------------- -------------------
  2.0      2013-02-18  Raphaël Semeteys  Initial version.
--------------------------------------- -------------------
~~~
    
In the QSOS.git repository, images are stored in the a sub-folder _Images_ placed in the same folder as the edoc.

When you add new sections or chapters to an edoc you have to modify the _Makefile_ file accordingly. This file is a input for the `Make` build system, in charge of producing PDF and Gitit exports. 

Let's look at the QSOS method's makefile _Method/en/Makefile_.

~~~{.Makefile .numberLines}
# Makefile for QSOS documentation
DOC=qsos
VERSION=2.0
LANG=en
TITLE=Qualification and Selection of Open source Software (QSOS)

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

* The _CHAPTERS_ parameter lists the Markdown files to be exported as a whole in a PDF document. Here, a specific head for PDF export but also the QSOS Manifesto, the edoc's changelog and a second appendix are added to the _PAGES_ list. Therefore they won't be included in the Gitit export.

* The _doc-pdf_ target, aimed through `make` (because it is the first target) or `make doc-pdf`, builds the PDF export from the _CHAPTERS_ list (through LaTeX and the use of the  _qsos-template_en.latex_ template^[You can modify this template if you want to fine tune or personalize the PDF export at the LaTeX level.]).

* The _gitit_ target uses the _PAGES_ list to build Gitit wiki pages:

    + A menu page (here: _qsos-2.0_en.page_) containing links to the other pages.

    + A page per Markdown file with the page's title based on the first line of the Markdown file.

* PDF file and Gitit pages are exported into the _dist/_ folder for further use.

* The _clean_ target deletes the temporary _dist/_ folder.

All makefiles of QSOS edocs follow that kind of structure and build process. 

QSOS edocs and generated export documents are distributed under the terms of the _GNU Free Documentation License_^[<http://www.gnu.org/copyleft/fdl.html>].
