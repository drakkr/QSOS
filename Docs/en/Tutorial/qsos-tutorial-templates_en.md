# Managing templates

## Overview of the templates

A template defines the structure or the grid of analysis of a QSOS evaluation. It contains criteria distributed as a tree on several sections.

The sections titled _Maturity_ and _Metadata_ are imposed by the QSOS method.

![_Maturity_ imposed in templates](Images/template-maturity_en.png)

The _Maturity_ section is an organized set of criteria unconditionally used to evaluate the maturity of a piece of software and the project in charge of its development, no matter what is the software or type of software. For more details on the criteria belonging to the _Maturity_ section, read the QSOS method itself. Therefore, even if you modify this section in your template, these modifications will be overwritten to guarantee its compliance within the method.

The _Metadata_ section is described further in chapter [Modifying the _Metadata_ section](#modifying-metadata-section).

It is reserved to the management of a set of information peculiar to the template itself (metadata), such as the authors, its version, its date of creation, its date of last modification or its language.

## Creating a new template

### Installing FreeMind

The free software FreeMind is used to create and modify the template as mindmaps. It's a software written in Java so it can be used on many platforms. Please read the official documentation^[<http://freemind.sourceforge.net/wiki/index.php/Documentation>] for more details on the installation of FreeMind.

Be careful though, the minimum version required by QSOS is 0.9.0.

### Getting the blank template

To create a new template, you need absolutely to start from the blank template in your language.

These blank templates respect the following naming convention: `template_[language].mm`. The English blank template is then `template_en.mm`.

The blank templates are available on the official website of the QSOS project at this address : __TODO__.

### Adding evaluation sections

Once the blank template is downloaded, open the `.mm` document with FreeMind. You'll find the two sections imposed by QSOS : _Maturity_ and _Metadata_.

To add new sections to your template, you just need to add and organize new nodes, while respecting the following formalism :

* an intermediate node allows to organize your criteria. In addition to the title, you can write a description with a sub-node in the _bubble_ format.

![_Bubble_ format to describe a node](Images/template-bubble_en.png)

* a final node (a leaf), is an evaluation criterion. It must respect the following conventions :

    1. the description of the criteria must also be in the bubble format ;

    2. the description of the score 0, 1 and 2 must be marked with the appropriate icons.

![Criteria description](Images/template-icons_en.png)

You're free to create and organize the criteria of an evaluation that are peculiar to a type of software. The main node of your template must be the name of the type of software. This name is the unique identifier of the template.

![Setting the name of the template](Images/template-name_en.png)

### Modifying the _Metadata_ section

![_Metadata_ section imposed in templates](Images/template-metadata_en.png)

Please modify the _Metadata_ of your template by entering the following information :

* _version_ : increase this value (according to the `n.m` rule) for each modification that must be applied on existing QSOS evaluations ;

* _language_ : language of your template (`fr` for French, `en` for English, etc.) ;

* _author_ : authors of the template, with their full name (_name_) and their email address (_email_) ;

* _creation_ : the date of creation of the template (with the `YYYY-MM-DD` format) ;

* _update_ : date of the last modification of the template.

### Modifying the _Maturity_ section

It is not possible to modify this section imposed by the QSOS method. Even if you modify the criteria in the blank template, these modifications will be overwritten.

### Saving the template

The FreeMind file of your template is in `.mm` format. You can then save it and use it as you want.

### Contributing the template

When you consider the template complete - at least for a first version, you can propose it to the QSOS community. To do that, go to this address : <http://o3s.qsos.org/backend/app/connect.php>. 

![Connection to O3S](Images/o3s-connect_en.png)

An O3S account is needed. If you don't have one, create one by clicking on _Sign On_.

![Uploading a template on O3S](Images/o3s-upload_en.png)

From then on, your template can be used by the community to create new evaluations. Read the chapter [Creating new evaluation](#creating-new-evaluation).

The template is automatically stored in the _Incoming_ repository of the QSOS reference. If it is considered as a good quality template, it will be approved by the community and moved in the _Master_ repository.

To contribute to a new version of your template, repeat the process (don't forget to increase the _version_ in the _Metadata_ section).

## Modifying an existing template

The existing templates are stored in the QSOS community reference. They are grouped in two distinct Git repositories :

* the *Incoming* repository : dedicated to the publication, the sharing and the use of evaluations and templates by the community. It is available to everyone via O3S. The only requirement is having an O3S account ;

* the *Master* repository : dedicated to the storage of the evaluations and template that are considered good quality elements and are approved by the moderators of the QSOS community.

You can browse the two repositories on O3S^[<http://o3s.qsos.org/backend/app/listRepo.php>] and download the templates you want.

![Browsing the templates reference](Images/o3s-list-templates_en.png)

Once you have the template, use _FreeMind_ to modify it. Read the chapter [Creating a new template](#creating-a-new-template) for more details on this topic.

