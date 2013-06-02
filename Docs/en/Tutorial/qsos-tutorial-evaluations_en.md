# Managing evaluations

## Overview of the evaluations

A QSOS evaluation is based on a template of a given version, it inherits the structure and the description of the criteria from. It is a file in `.qsos` format that is manipulated via the _XulEditor_ tool (see [Installing _XulEditor_](#installing-xuleditor)).

An evaluation based on a version of a template can be updated by another version of the same template, without loosing any existing data (see [Applying a new version of a template](#applying-a-new-version-of-a-template)).

## Creating a new evaluation

### Installing _XulEditor_

_XulEditor_ can be installed in two ways :

* as a Firefox extension ;

* as a XulRunner standalone application.

While the first way is easier, the second has the advantage of allowing you to run several _XulEditor_ instances.

#### Installing _XulEditor_ as a Firefox extension

You just have to open the installation file^[<http://www.qsos.org/tools/xuleditor-firefox-2.0.xpi>] in your Firefox browser^[At the time of writing, the latest version of _XulEditor_ is 2.0. Please install the latest version when you read this tutorial.].

Once the extension is installed, it can be launched in the menu « _Tools/QSOS Editor_ » of the _Firefox_ browser.

#### Installing _XulEditor_ as a standalone application

In order to use _XulEditor_ as a standalone application, Mozilla Xulrunner must be installed.

Visit the official Mozilla website^[ <https://developer.mozilla.org/en-US/docs/XULRunner>] to install Xulrunner.

Then get the application archive^[<http://www.qsos.org/tools/xuleditor-application-2.0.zip>] and decompress it where you want.

The _XulEditor_ can then be launched via the scripts `xuleditor` or `xuleditor.bat` depending on your operating system.

### Using _XulEditor_

![_Files_ tab of _XulEditor_](Images/xuleditor-tab-file_en.png)

In the _Files_ tab, you can create a new evaluation from a template coming from either your hard drive or from the QSOS reference. The second case is detailed hereafter.

In order to do that, click on the _Remote template_ button and browse the QSOS templates reference.

![Browsing the QSOS templates reference](Images/xuleditor-remote-template_en.png)

You can filter the templates of the reference by the repository (_Master_ or _Incoming_) and by language.

Once the template is selected, you can the start entering your evaluation by completing the fields of the different tabs.

The _General_ tab contains information relating to the evaluated software (name, version, main license, etc.) and to the evaluation itself (template, authors).

![_General_ tab of _XulEditor_](Images/xuleditor-tab-general_en.png)

You see that the name and the version of the template used for this evaluation are displayed but cannot be modified. For more details on how to update the template of an existing evaluation, read the chapter [Applying a new version of a template](#applying-a-new-version-of-a-template).

The _criteria_ tab allows you to browse in the tree of the criteria and to evaluate them :

* by assigning a score between 0 and 2 depending on the meaning described by the authors of the template you use ;

* by giving the reason and the source of the score.

![_Criteria_ tab of _XulEditor_](Images/xuleditor-tab-criteria_en.png)

Please give a reason of the score in the _Comments_ field. It will make your evaluation more relevant, more useful and increases its chances to be approved by the QSOS community.

The _Chart_ tab allows you to browse in the tree of criteria as a radar chart where you can click on the sections to see the sub-criteria or browse via the breadcrumbs.

![_Chart_ tab of _XulEditor_](Images/xuleditor-tab-graph_en.png)

The criteria colored in red are those that have not been evaluated yet. It allows you to quickly identify the missing parts to complete your evaluation.

### Saving an evaluation

In the _Files_ tab of _XulEditor_, you can save you evaluation on your hard drive (_Save locally_ button) to possibly edit it later (_Local evaluation_).

### Contributing an evaluation

When you consider your evaluation is complete - at least for a first version, you can then propose it to the QSOS community.

There are two ways to do it. The first is, like with templates, to connect to the QSOS backend^[<http://backend.qsos.org>]. 

![Uploading an evaluation to the QSOS reference](Images/o3s-upload-eval_en.png)

This requires an account on O3S. If you don't have one, create one on the same website.

If you already have an O3S account, you can also save your evaluation directly on the QSOS reference with _XulEditor_, via the _Save remotely_ in the _Files_ tab.

![Saving on the QSOS reference with _XulEditor_](Images/xuleditor-save-remote_en.png)

![Visible timeline of your contribution in O3S](Images/o3s-timeline_en.png)

From this point, your evaluation is visible by the community in the _Incoming_ repository of the QSOS reference. If it is considered as complete and objective, it will be approved by the community and moved to the _Master_ repository.

To contribute a new version of your evaluation, repeat the process.

## Modifying an existing evaluation

_XulEditor_ allows you to modify a QSOS evaluation coming either from your hard drive or the QSOS reference. The latter is described hereafter.

In the _Files_ tab, click on the _Remote evaluation_. You can then browse in the QSOS reference by filtering the evaluations by the type of software, the repository (_Master_ or _Incoming_) and the language.

![Browsing the QSOS evaluations reference](Images/xuleditor-remote-evaluation_en.png)

Once the evaluation is selected, you can then manipulate it and modify it in _XulEditor_.

You cannot overwrite an evaluation that is not yours. The reference doesn't allow this but it will tell you who contributed this evaluation. You can contact her then.

![Evaluation Overwrite attempt](Images/xuleditor-writeremote-error_en.png)

If you can't contact this user or she hasn't replied, contact one of the QSOS moderators who will arbitrate and unlock the situation.
TODO : add the moderators' mailing list.

## Applying a new version of a template

_XulEditor_ allows you to update the template used in an evaluation. It allows to inject developments brought into the template and also to ease the translation of the evaluation (by applying an already translated template).

To do that, you first have to open the evaluation that you want to update (_Local evaluation_ button or _Remote evaluation_ in the _Files_ tab). Then select the new version of the template to apply, whether it's coming from your hard drive (_Local template_ button) or from the QSOS reference. This is the latter case that is described hereafter.

The _Remote template_ allows you to browse the QSOS templates reference.

![Browsing the QSOS templates reference](Images/xuleditor-remote-template_en.png)

You can filter the templates of the reference by repository (_Master_ or _Incoming_) and by language.

![Error while applying a wrong type of template](Images/xuleditor-template-error_en.png)

If you select a template that has a different type from the initial one's, _XulEditor_ prevent you to apply it and displays an error message.

![Confirmation request if the template is in another language](Images/xuleditor-template-lang_en.png)

If the new template is in a different language from the initial one's, _XulEditor_ requests a confirmation before modifying your evaluation.

![Confirmation request of the modification](Images/xuleditor-template-confirm_en.png)

And in every case, before modifying your evaluation, _XulEditor_ requests a confirmation.

Please note that it is not forbidden to apply an older template.

## Visualizing an evaluation in web mode

The O3S web application allows, among other things, to visualize the evaluation of the QSOS reference in web mode. This application provides many other features, linked to QSOS comparisons. Read the chapter [Managing comparisons](#Managing-comparisons) for a more detailed description of O3S.

Let's consider that you have browsed 03S to the evaluation you want to visualize in web mode.

![O3S Homepage](Images/o3s-evaluation_en.png)

03S provides three evaluation visualization formats :

* XML format (`.qsos`) ;

* HTML format ;

* FreeMind format (`.mm`).

### Visualization in XML format

When you select this mode of visualization in 03S, and you have Firefox with the _XulEditor_ extension, the evaluation is opened directly in the editor.

![Visualization in XML format](Images/o3s-evaluation-xml_en.png)

In every case, the XML file is also displayed by your browser.

### Visualization in HTML format

When you select this mode of evaluation in O3S, the evaluation is displayed in your browser as an HTML page.

This page allows to fold and unfold branches of the tree of criteria to ease reading of the evaluation.

### Visualization in FreeMind format

When you select this mode of visualization in O3S, the evaluation is displayed in your browser as a mindmap in FreeMind format (`.mm`).

![Visualization in FreeMind format](Images/o3s-freemind-evaluation_en.png)

This page allows you to take the following actions :

* browse the evaluated criteria tree by clicking on the elements of the mindmap to fold/unfold ;

* use the browsing menu to search by keyword or to move the visualization window (some evaluations can indeed be bigger than the windows' frame when it's fully unfolded) ;

* use the displaying menu to adjust the size of the font, to adjust the size of the map and to change the background color ;

* save the `.mm` file on your hard drive, by clicking on the title of the mindmap.

Although it's a file in FreeMind format, please note that you do not visualize and do not save a QSOS template but a representation of an evaluation as a mindmap.

Please note that this type of visualization requires that your browser supports Flash.

