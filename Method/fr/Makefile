# Makefile for QSOS documentation

all: doc-odt doc-pdf doc-html

doc-odt:
	pandoc --toc -o qsos-2.0_fr.odt qsos-head_fr.md qsos-manifesto_fr.md qsos-changelog_fr.md qsos-intro_fr.md qsos-process_fr.md qsos-step1_fr.md qsos-step2_fr.md qsos-step3_fr.md qsos-step4_fr.md qsos-community_fr.md qsos-appendixA_fr.md qsos-appendixB_fr.md

doc-pdf:
	pandoc -N --toc --template=qsos-template_fr.latex --variable lang=french -o qsos-2.0_fr.pdf qsos-head_fr.md qsos-manifesto_fr.md qsos-changelog_fr.md qsos-intro_fr.md qsos-process_fr.md qsos-step1_fr.md qsos-step2_fr.md qsos-step3_fr.md qsos-step4_fr.md qsos-community_fr.md qsos-appendixA_fr.md qsos-appendixB_fr.md

doc-html:
	pandoc --self-contained -o qsos-2.0_fr.html qsos-head_fr.md qsos-manifesto_fr.md qsos-changelog_fr.md qsos-intro_fr.md qsos-process_fr.md qsos-step1_fr.md qsos-step2_fr.md qsos-step3_fr.md qsos-step4_fr.md qsos-community_fr.md qsos-appendixA_fr.md qsos-appendixB_fr.md
