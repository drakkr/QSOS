# Managing comparisons

The QSOS method was designed to compare different pieces of software of the same type, in order to make an informed decision.
This comparison between evaluations from the same template take the context of the user into account thanks to a weighting system that can be used on the different criteria of the template.

The O3S web application allows to make weightings, comparisons and to export them into different formats.

Two versions of O3S are available, each is connected to one of the two repositories of the QSOS reference :

* the official version^[TODO : <http://o3s.qsos.org/master/>], connected to the _Master_ repository ;

* the sandbox version^[TODO : <http://o3s.qsos.org/incoming/>], connected to the _Incoming_ repository.

## Creating a new comparison

Connect to the O3S instance you want. The list of the evaluations of the instance is displayed, filtered by language.

![03S Home page](Images/o3s-index_en.png)

Select the domain you're interested in. The list of the evaluations made with the version of the template you selected is displayed.

![Comparison management page](Images/o3s-list-evaluations_en.png)

On this page you can take several actions :

* visualize in your browser one of the listed evaluations in XML (`.qsos`)^[If you use Firefox and the XulEditor extension is installed, it can directly be opened.], HTML or FreeMind formats ;

* weight the template in order to customize your comparison ;

* select the evaluations to be integrated in your comparison ;

* visualize your comparison in web mode as an HTML table or an SVG chart ;

* export your evaluation into OpenDocument format.

## Weighting a comparison

The _weight the template_ button displays a page allowing you to manage your weighting.

![Comparison weighting management](Images/o3s-weightings_en.png)

On this page you can :

* enter the weights of each criterion of the template ;

* save your weighting and associate it with the comparison ( _Save_ button) ;

* export/import your weighting into XML (`.qw`) so it can be reused. ( _Export into XML_ and _Browse..._ and _Load_) ;

* and finally go back to the comparison management page ( _Back_ button).

## Visualizing a comparison in web mode

![Visualization buttons in web mode](Images/o3s-buttons-web_en.png)

On your comparison management page, you can visualize it in your browser in several modes :

* as a comparative table ;

* as a radar chart ;

* as a QSOS quadrant.

### Visualizing a comparative table

The _Online comparison_ button triggers the display of a comparative table of the evaluations that you have selected with your weighting.

![Visualization as a comparative table](Images/o3s-comparison-table_en.png)

This table is dynamic. It allows you to take the following actions :

* show or hide the _Comments_ columns of the different evaluations ;

* adjust the size of the font used in display ;

* toggle in radar charts visualization ;

* go back to your comparison management page ( _Back_ button).

### Visualizing a dynamic radar chart

The _Comparative chart_ button triggers the display of the comparison as a radar chart.

![Visualization as a dynamic radar chart](Images/o3s-radar_en.png)

This chart is dynamic. It allows you to take the following actions :

* browse the comparison in « _Drill Down_ » mode : by clicking on the name of the criteria you want its sub-criteria to be displayed ;

* browse the comparison in « _Drill Back_ » mode: via the link _back to upper level_.

* go back to your comparison management page (_Back_ link).

Please note that this type of visualization requires that your browser respects the SVG standard.

You can save the SVG charts on your hard drive by using the save function of your browser.

### Visualizing a QSOS quadrant

The O3S application allows to visualize your comparison as a QSOS quadrant.

![Visualization as a QSOS quadrant](Images/o3s-quadrant_en.png)

It's the position of the different evaluations that you've selected on two axes :

* _functional coverage_ : the value used on the X-axis is the weighted mean of all the criteria of the template, except the criteria of the _Maturity_ section ;

* _Maturity_ : the value used on the Y-axis is the weighted mean of the criteria of the _Maturity_ section, imposed by the method.

The ellipse, corresponding to the evaluated software, are clickable zones that redirect you to the visualization of the details of each evaluation.

Please note that this type of visualization requires that your browser respects the SVG standard.

You can save the QSOS quadrant on your hard drive by using the save function of your browser.

## Exporting a comparison into OpenDocument formats

![Export into OpenDocument formats button](Images/o3s-buttons-opendocument_en.png)

On your comparison management page, you can export it into the following OpenDocument formats :

* spreadsheet : ODS format ;

* presentation : ODP format ;

* text : ODT formats.

### Exporting into ODS format

On your comparison management page, a click on the _Export into ODS_ triggers the generation of the export of your comparison into the _OpenDocument Spreadsheet_ format.

You can save the result as an `.ods` file on your hard drive.

![Spreadsheet exported by O3S](Images/o3s-ods_en.png)

The exported spreadsheet contains several tabs :

* _Home_ : the Front-Cover of the document presenting the metadata of the comparison and of its ODS export (date, template version, tabs' presentations and license) ;

* _Synthesis_ : tab synthesizing the comparison in terms of scores and allowing to modify the weight of the criteria ;

* _Criteria_ : explanation of the criteria used in the comparison, based on the data of the template ;

* a tab for each evaluated piece of software : presenting the details of the evaluation (metadata, scores and comments).

This dynamic comparison can be used autonomously to customize your comparison by modifying the weighting.

Please note that in order to guarantee the community aspect of the effort provided to make this exported document (creation and modification of the template, creation and modification of the evaluations, development and maintenance of the tools _XulEditor_ and O3S), it is distributed by the QSOS project under the terms of the _GNU Free Documentation License_^[<http://www.gnu.org/copyleft/fdl.html>].

### Exporting into ODP format

On your comparison management page, a click on the button _Export into ODP_ triggers the generation of the export of your comparison into the _OpenDocument Presentation_ format.

You can save the result as an `.odp` file on your hard drive.

![Presentation exported by O3S](Images/o3s-odp_en.png)

The presentation is composed of different types of slides :

* the Front-Cover of the presentation ;

* the table of content slides to position and organize the other slides ;

* the slides relating to the scope of the comparison : list of analyzed solutions, presentation of the template (FreeMind mindmap and an explanation for each main section of the template) ;

* for each analyzed solution, slides giving more details (presentation of the project, minmaps and text to be completed for every main section of the evaluation) ;

* slides relating to the comparison of the solutions (radar charts and text to be filled for every main section of the evaluation, QSOS quadrant and text to be completed) ;

* slides relating to the license of the presentation and to the credits to the QSOS community (authors of the evaluations used to generate the presentation's backbone).

It is a backbone that can be used to produce a final document by entering your analysis and your synthesis in the text zones to be completed.

Please note that in order to guarantee the community aspect of the effort provided to make this exported document (creation and modification of the template, creation and modification of the evaluations, development and maintenance of the tools _XulEditor_ and O3S), it is distributed by the QSOS project under the terms of the _GNU Free Documentation License_^[<http://www.gnu.org/copyleft/fdl.html>].

### Exporting into ODT format

On your comparison management software, a click on the _Export into ODT_ button triggers the generation of the export of your comparison into _OpenDocument Text_ format.

You can save the result as an `.odt` file on your hard drive.

![Report exported by O3S](Images/o3s-odt_en.png)

The report is composed of different chapters :

* _Introduction_ : chapter briefly presenting the purpose of the document as well as the QSOS method and containing text to be completed ;

* _Used analysis template_ : presentation of the template (FreeMind mindmap and details on every main section of the template) ;

* _Identified solutions_ : presentation of the scope of the comparison and details on every evaluation (presentation of the project, mindmaps and text to be filled for every main section of the evaluation) ;

* _Synthesis_ : comparison of the different solutions (radar charts and text to be completed for every main section of the evaluation, QSOS quadrant and text to be completed) ;

* _Appendix_ : presentation a little more detailed of the QSOS method ;

* _Credits_ : attribution to the authors of the evaluations used to generate the backbone of the document.

It is a backbone that can be used to produce a final document by entering your analysis and your synthesis in the text zones to be completed that are _highlighted_ in the document.

Please note that in order to guarantee the community aspect of the effort provided to make this exported document (creation and modification of the template, creation and modification of the evaluations, development and maintenance of the tools _XulEditor_ and O3S), it is distributed by the QSOS project under the terms of the _GNU Free Documentation License_^[<http://www.gnu.org/copyleft/fdl.html>].
