# Makefile for QSOS tutorial

all: tutorial-pdf tutorial-odt

tutorial-pdf:
	pandoc -N --toc --template=../../../Method/fr/qsos-template_fr.latex --variable lang=french -o qsos-tutorial-2.0_fr.pdf qsos-tutorial-head_fr.md qsos-tutorial-intro_fr.md qsos-tutorial-templates_fr.md qsos-tutorial-evaluations_fr.md qsos-tutorial-comparisons_fr.md

tutorial-odt:
	pandoc --toc -o qsos-tutorial-2.0_fr.odt qsos-tutorial-head_fr.md qsos-tutorial-intro_fr.md qsos-tutorial-templates_fr.md qsos-tutorial-evaluations_fr.md qsos-tutorial-comparisons_fr.md
