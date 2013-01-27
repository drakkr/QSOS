# Makefile for QSOS tutorial

all: tutorial-pdf tutorial-odt

tutorial-pdf:
	pandoc -N --toc --template=../../Method/fr/qsos-template_fr.latex --variable lang=french qsos-tutorial-2.0_fr.md -o qsos-tutorial-2.0_fr.pdf

tutorial-odt:
	pandoc --toc -o qsos-tutorial-2.0_fr.odt qsos-tutorial-2.0_fr.md
