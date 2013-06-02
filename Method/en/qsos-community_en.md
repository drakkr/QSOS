# The QSOS Project

## A free and community project

In addition to the method, QSOS is also a free and community project dedicated to the collaborative watch of free and open source software.

Hence, the main purposes of the project are :

* manage the evolution of the method and of the evaluation file format ;

* centralize the references, notably the storage of templates, the identity cards and evaluations ;

* provide tools to help apply the QSOS method ;

* Help user using the method via best practices and communication hubs.

## Tools and Formats

The free project QSOS also provide tools to apply the process of the method and to make the collaboration easy.

The diagram below describes the existing tools and formats.

![QSOS formats and tools](Images/tools.png)

### Templates

__FreeMind__

The template are functional coverage grids specific to a software family. Before evaluating a piece of software, a well-suited template is needed.

The QSOS project uses mind maps to design and document its templates. The free software FreeMind^[<http://freemind.sourceforge.net>] has been chosen because of its portability and its XML format allowing the transformation of the templates into `.qsos` format (described hereafter) thanks to XSL.

__`.mm` Format__

The template are described and stored in the format used by FreeMind (`.mm`).

This format is described on the freemind official website . It's an XML format used by QSOS as a pivot format for templates. The blank evaluations used to analyze software are generated from this format via XSL transformations.

![Criteria description](Images/freemind.png)

The mind maps representing QSOS templates must comply with a specific formalism in order to be transformed in evaluations.

1. the criteria descriptions must be bubbles (Format/Bubble with FreeMind) ;

2. the score descriptions (0, 1 and 2).

The XSL file allowing to transform templates into evaluations is available on the QSOS project website. FreeMind allows to apply the transformation via the menu File/Export/Using XSLT...

### Evaluations

__XulEditor__

![XulEditor screenshots](Images/xuleditor.png)

XulEditor is a QSOS evaluation entry and management tool. It allows to perform the following actions :

* create a new evaluation from a template in `.mm` format (local template or from the QSOS reference) ;

* open and modify an existing evaluation (local evaluation or from the QSOS reference) ;

* apply a new version of a template on an evaluation (without losing existing data) ;

* save an evaluation (locally or in the QSOS reference).

XulEditor doesn't allow to modify a `.mm` template and only deals with evaluations in the `.qsos` format.

It is an application using the Mozilla technology. It can be deployed as an extensions for the web browser Firefox or as a XulRunner application.

Visit the QSOS project website for more details on the installation of XUlEditor.

__O3S (Open Source Selection Software)__

03S is a web application allowing to visualize, weight and compare QSOS evaluations according to the method process.
It allows to visualize, compare and export the QSOS evaluations in OpenDocument format, and to generate charts in SVG format.

![Export in OpenDocument Spreadsheet format](Images/ods.png)

It is available online at this URL : <http://www.qsos.org/o3s/>.

It is also possible to install a local 03S instance in your organization. Visit the QSOS project website to go further.

__`.qsos` format__

The evaluations are described and stored in an XML pivot format specific to QSOS. The XML schema is available on the QSOS project website. This chapter describes the structure principles.

The file extension is `.qsos`.

The main tag is `<document/>`, it contains :

* a header `<header/>` containing the metadata related to the evaluation (author, language, used template, QSOS version, template version, date of creation, date of validation...) ;

* one or several sections (`<section/>`) :

    + each section is composed of evaluation criteria (`<element/>`) that can be nested, and descriptions (`<desc/>`) ;
    
    + in this tag tree, the leaves (criteria that don't have children) contain the meaning of the scores O, 1 and 2 (`<desc0/>`, `<desc1/>` and `<desc2/>`), the score (`<score/>`) and a comment to give the reason and to cite the sources(`<comment/>`).
    
An example of the structure is below :

~~~ {.xml}
<?xml version="1.0" encoding="UTF-8"?>
<document>
   <header>
      <authors>
         <author>
            <name>Name of the author</name>
            <email>Email address of the author</email>
         </author>
         <!-- Other <author/>  -->	 
      </authors>
      <dates>
         <creation>Date of Creation</creation>
         <validation>Date of Validation</validation>
      </dates>
      <appname>Application/software name</appname>
      <desc>short description of the software</desc>
      <release>Software version</release>
      <licenseid>Identifier of the main license</licenseid>
      <licensedesc>Name of the main license</licensedesc>
      <url>URL of the software website</url>
      <demourl>URL of the demo website</demourl>
      <language>language of the evaluation : en, fr...</language>
      <qsosappname>CPE identifier of the version</qsosappname>
      <qsosformat>used QSOS format, currently: 2.0</qsosformat>
      <qsosspecificformat>template version</qsosspecificformat>
      <qsosappfamily>template name</qsosappfamily>
   </header>
   <section name="maturity" title="Maturity">
      <!-- <section/> imposed by QSOS -->     
   </section>     
   <section name="Unique-identifier-1" title="Section name">
      <element name="Unique-identifier-2" title="Criterion name">
         <desc>Description of the criterion</desc>
         <element name="Unique-identifier-3" title="Sub-criterion name">
         <desc>Description of the sub-criterion</desc>
            <desc0>meaning of the score 0</desc0>
            <desc1>meaning of the score 1</desc1>
            <desc2>meaning of the score 2</desc2>
            <score>Score : 0, 1 or 2</score>
            <comment>Reason and sources</comment>
         </element>
         <!-- Other <element/> -->
      </element>
      <!-- Other <element/> -->
   </section>
   <!-- Other <section/> -->
</document>
~~~

So, it is an XML tree composed of a header (`<header/>`) and of sections (`<section/>`) containing elements (`<element/>`). The leaves of this tree are criteria that can be scored _0_, _1_ or _2_.

This format is used as a pivot by the tools provided by the QSOS project to export into other formats, such as HTML, SVG or OpenDocument.

The detailed structure of this format is described in an XSD schema, available on the QSOS project website.

__QSOS reference and engine__

The QSOS engine is a chain of tools to validate, control and publish the QSOS evaluations and the templates stored in the reference.

The reference is composed of two Git repositories dedicated to the storage of the evaluations templates :

* the *Incoming* repository : reserved for publication, sharing and manipulation of the evaluations and templates by the community. It is available to everyone via O3S and only requires the creation of a user account ;

* the *Master* repository : dedicated to the storage of the evaluations and templates considered as good quality elements and that have been approved by a moderator of the QSOS community.

In addition to these two repositories reserved for the documents generated and used by the QSOS method, the project also use a Git repository for the development of its tools and another one for its documentation.

The documentation is written in Markdown^[<http://daringfireball.net/projects/markdown/>], used as a pivot format by Pandoc^[<http://johnmacfarlane.net/pandoc/>] to export into PDF and HTML, and by Gitit^[<http://gitit.net>] for the wiki of the project.

To sum up, the Git repositories are :

------------------------------------------------------------------------------
Repository                     Purpose
------------------------  ----------------------------------------------------
QSOS.git                  Tools and formats

QSOS-Incoming.git         Templates and evaluations in sandbox mode

QSOS-Master.git           Templates and evaluations approved by the community

Drakkr.git                Documentation of QSOS and the other projects within Drakkr^[See appendix B for more details on the Drakkr framework]
------------------------------------------------------------------------------

Visit the QSOS project's website to clone these repositories.

## How to contribute

The purpose of the QSOS project is to mutualize the effort on the free and open source software watch. It is then resolutely a community project: the more contributors there are, the greater are the number, the quality and the objectivity of the evaluations.

You can contribute to the project by :

* creating or updating templates and evaluations ;

* translating templates, evaluations or the documentation ;

* getting involved in the development of the tools ;

* promoting the method and the project.

Visit the QSOS project's website for more details on the governance of the QSOS community.
