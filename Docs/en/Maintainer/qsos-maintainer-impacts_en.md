# Impact analysis of some modifications

## Modification of the QSOS Maturity axis

When you modify the Maturity section, it has impacts on several other QSOS components:

* the _criteria-maturity_[language].xml_ reference files: it is probably in one of them where you'll make your initial modifications

* the method itself: part of the qsos-appendix4-[language] can be generated by applying the _/Tools/o3s/formats/xml/xslt/evaluation-template-markdown.xsl_ transformation to previous files

* other XSLT files in _/Tools/o3s/formats/xml/xslt/_

* the _/Tools/xuleditor/chrome/content/compatibility.js_ file, by using the `sed 's/"/\\"/g' <file.xslt> | sed 's/$/\\/g` command to transform XSLT files to strings compatible with JavaScript

* the template bootstraps in _/Tools/o3s/formats/xml/template_

Depending on the nature of your modifications, you also might have to modify other QSOS documents like the tutorial or this very maintainer guide.

## Modification of the QSOS format

If you modify the `.qsos` format, it also has impacts on several other QSOS components:

* files in _/Tools/o3s/formats_: 

     + XML files: XSL transformations, XSD, template bootstraps

     + PHP libraries wrapped around the XML files 

* the method itself

* the QSOS tools: XulEditor, O3S
