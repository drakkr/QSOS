% QSOS TODO list
% ![Logo](Images/QSOS.png)
% Last update: 13/01/2013

# QSOS Backend

* Upload: 
    + Reject incomplete evaluations (score, header) or not with not enoughr comments
    + Templates: differentiate additions from update and allow uodates only by the same user

# XulEditor

* _Criteria_ Tab: label _Comments_ should be in bold font

* Backport Tim's reverse engineering of templates

# Documentation

* Meta: most of documentation should be in English, some of it in French also

* Method:
    + Update the text itself
        + Resize images
    + Translate it in English

* Installation guide(s) of QSOS tools
    + XulEditor: document installation as a xulrunner application (`xulrunner -install-app /path/to/xuleditor/`) and ideally make `.exe`, `.rpm` and `.deb` packages
    + O3S

* QSOS Starting Guide
    + Merge with current wiki
    + Make a complete demo of the QSOS lifecycle

* QSOS Maintainer Guide
    + Impacts of a QSOS format modification (XSD, XSLT, tools)
    + Impacts of the Maturity axis of criteria (XML documents of reference: `criteria-maturity_*.xml`, XulEditor's external and internal XSLT, template bootstraping, method itself)
    + How the project manages version; method, formats, documentation, templates, evaluations...

* QSOS translation and localization guide (only in English)
    + Translation of the method, XML files (`criteria-maturity_*.xml`, XSLT)
    + Translation of tools gui (O3S, XulEditor)
    + Add a new supported language in tools (O3S, XulEditor)
    + Documentation and website
    + Translate templates and evaluations (try to do some automation here? Google translate?)

* FAQ, Tips and Tricks, Best pratices...
    + Common probems: template -> evaluation (errors, `I have to Maturity sections`, lost metadata...), Remote Save (XSD errors, encoding, credentials...)
    + Reuse best pratice of current wiki regarding how to design a template
    + Clarify legal aspects: `Are QSOS evaluation under FDL?`, `Are exported QSOS comparison under FDL?`

# Website

* Change the structure: QSOS goals and specifics should appear on the first webpage
* Use Gitit (<https://github.com/jgm/gitit>) for the Wiki
* Develop a function in 03S to automatically update templates and evaluations lists
* Decide if WordPress MU is still usefull in the DrakkR project

# Recurring annoying problems

* Git errors: <http://stackoverflow.com/questions/1918524/error-pushing-to-github-insufficient-permission-for-adding-an-object-to-reposi>
