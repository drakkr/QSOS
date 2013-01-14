# Makefile for QSOS documentation

all: doc-odt doc-pdf doc-html

doc-odt:
	pandoc --toc -o qsos-2.0_fr.odt qsos-2.0_fr.md

doc-pdf:
	pandoc -N --toc --template=qsos-template_fr.latex qsos-2.0_fr.md -o qsos-2.0_fr.pdf

doc-html:
	pandoc --self-contained -o qsos-2.0_fr.html qsos-2.0_fr.md
