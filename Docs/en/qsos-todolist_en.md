% QSOS TODO list
% ![Logo](Images/QSOS.png)
% Last update: 2013-02-18

# QSOS Backend

* Upload: 
    + Reject incomplete evaluations (score, header) or without enough comments
    + Templates: differentiate additions from update and allow updates only by the same user

* Create QSOS repositories on GitHub

# XulEditor

* _Criteria_ Tab: label _Comments_ should be in bold font

* Backport Tim's reverse engineering of templates

# Documentation

* Meta: most of documentation should be in English, some of it in French also

* Method:
    + Resize images
    + Translate it into English

* Installation guide(s) of QSOS tools
    + XulEditor: document installation as a xulrunner application (`xulrunner -install-app /path/to/xuleditor/`) and ideally make `.exe`, `.rpm` and `.deb` packages
    + O3S

* QSOS Tutorial
    + Translate into English

* QSOS Maintainer Guide
    + Finish redaction

* FAQ, Tips and Tricks, Best practices...
    + Common problems: template -> evaluation (errors, `I have to Maturity sections`, lost metadata...), Remote Save (XSD errors, encoding, credentials...)
    + Reuse best practice of current wiki regarding how to design a template
    + Clarify legal aspects: `Are QSOS evaluation under FDL?`, `Are exported QSOS comparison under FDL?`

# Website

* Change the structure: QSOS goals and specifics should appear on the first webpage
* Use Gitit (<https://github.com/jgm/gitit>) for the Wiki
* Develop a function in 03S to automatically update templates and evaluations lists
* Decide if WordPress MU is still useful in the DrakkR project

# Recurring annoying problems

* Git errors: <http://stackoverflow.com/questions/1918524/error-pushing-to-github-insufficient-permission-for-adding-an-object-to-reposi>
