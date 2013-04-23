# Makefile for QSOS Maintainer Guide

DOC=qsos-maintainer-guide

VERSION=2.0

LANG=en

TITLE=QSOS Maintainer Guide

#List of edocs to be included in Gitit export
PAGES=qsos-maintainer-intro_$(LANG).md qsos-maintainer-edoc_$(LANG).md qsos-maintainer-tools_$(LANG).md qsos-maintainer-impacts_$(LANG).md qsos-maintainer-web_$(LANG).md qsos-maintainer-i18n_$(LANG).md

#List of edocs to be included in other exports
CHAPTERS=qsos-maintainer-head_$(LANG).md qsos-maintainer-changelog_$(LANG).md $(PAGES) qsos-maintainer-drakkr_$(LANG).md

DOCNAME=$(DOC)-$(VERSION)_$(LANG)

doc-pdf:
	mkdir -p dist
	pandoc -N --toc --template=../../../Method/$(LANG)/qsos-template_$(LANG).latex -o dist/$(DOCNAME).pdf $(CHAPTERS)

doc-odt:
	mkdir -p dist
	pandoc --toc -o dist/$(DOCNAME).odt $(CHAPTERS)

doc-html:
	mkdir -p dist
	pandoc --self-contained -o dist/$(DOCNAME).html $(CHAPTERS)

gitit:
	mkdir -p dist
	cp -R Images dist/.
	#Create summary Gitit page
	echo '---' > dist/$(DOCNAME).page
	echo 'title: $(TITLE)' >> dist/$(DOCNAME).page
	echo '...' >> dist/$(DOCNAME).page
	echo -e '\n' >> dist/$(DOCNAME).page

	$(foreach PAGE, $(PAGES), echo -e "[$(subst # ,,$(shell head -1 $(PAGE)))]($(basename $(PAGE))) \n" >> dist/$(DOCNAME).page ;)

	#Create Gitit pages with titles
	$(foreach PAGE, $(PAGES), echo -e "---\ntitle: $(subst # ,,$(shell head -1 $(PAGE)))\n...\n" > dist/$(basename $(PAGE)).page ; cat $(PAGE) >> dist/$(basename $(PAGE)).page ;)

clean:
	rm -Rf dist/
