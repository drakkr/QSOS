# Introduction

This document aims to give directions and guidance regrading maintenance of the QSOS project itself. We do not consider here maintenance of data components like QSOS templates or evaluation which are managed in the dedicated Git repositories _QSOS-Master.git_ and _QSOS-Incoming.git_. 

Components discussed here are called core components and are managed in the _QSOS.git_: 

* Documentation related to the QSOS project itself: method, tutorial, roadmap...

* QSOS formats and related XML documents like XSD schemas, XSL transformations...

* Tools developed to help use the QSOS method, manipulate its formats and manage data components.

# Version naming convention

Versions of QSOS core components (documents, formats and tools) follow the X.Y pattern:

* X is a number representing a major version.

* Y is a number representing a minor subversion of the major version.

Majors versions are generally set by the QSOS format itself (`.qsos` file format) since major changes on this format imply modification of almost every other component of the project.

So the rule is that when the QSOS format changes its major version, every other component should also change to this new major version.

Impacts of a minor version is generally restricted to a specific component. Therefore QSOS components don't necessarily share minor versions.

# The QSOS.git repository

As explained before, QSOS.git should not be mistaking with repositories for QSOS data components. Its purpose is to store and manage maintenance of QSOS core components.

The QSOS.git repository is structured like this:

* Docs: folder for QSOS documentation, split by language.

* Method: folder containing the QSOS method itself, split by language.

* Tools: folder for QSOS tools and format.

The repository is available at this URL: <https://github.com/drakkr/QSOS>.

