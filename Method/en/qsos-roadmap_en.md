% QSOS Roadmap
% ![Logo](Images/QSOS.png)
% Last update: 13/01/2013

# QSOS XML Format

* Add a _highlight_ attribute [_true_|_false_] to the `<comment/>` tags
    + Used to identified perticular comments to be included in ODP and ODT exports

* Add a `<source/>` tag to `<element/>` to detail source(s) used to score the criteria

* Impacts on both Editor and O3S

* New XSD and XSLTs

# QSOS Repositories

* Repositories could be navigated with language filters

* Create QSOS repositories on GitHub

# O3S

* Implement the new QSOS format (ODP and ODT exports)

* Automatically add the CPE Id (<http://cpe.mitre.org>) in the `<qsosappname/>` tag via a call to OSC Web Service

* Implement syncrhonization with local instances of 03S (Git + database)

# XulEditor

* Implement the new QSOS format : `<comment highlight=''/>`, `<source/>`, `<qsosappname/>`

# Mailing list

* (Re)create existing mailing lists on GitHub

* Create a new mailing list for QSOS validators (and modify Tools/o3s/app/upload.inc to add this address after the TXT_UPLOAD_ERROR_ALREADY error message)
