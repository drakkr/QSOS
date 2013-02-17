# Makefile for QSOS documentation

DOC=qsos

VERSION=2.0

LANG=fr

TITLE=Méthode de Qualification et Sélection de logiciels Open Source (QSOS)

#List of edocs to be included in Gitit export
PAGES=qsos-intro_$(LANG).md qsos-process_$(LANG).md qsos-step1_$(LANG).md qsos-step2_$(LANG).md qsos-step3_$(LANG).md qsos-step4_$(LANG).md qsos-community_$(LANG).md qsos-appendixA_$(LANG).md

#List of edocs to be included in other exports
CHAPTERS=qsos-head_$(LANG).md qsos-manifesto_$(LANG).md qsos-changelog_$(LANG).md  $(PAGES) qsos-appendixB_$(LANG).md

DOCNAME=$(DOC)-$(VERSION)_$(LANG)

doc-pdf:
	mkdir -p dist
	pandoc -N --toc --template=qsos-template_$(LANG).latex --variable lang=french -o dist/$(DOCNAME).pdf $(CHAPTERS)

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