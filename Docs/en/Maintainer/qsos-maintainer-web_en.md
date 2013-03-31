# QSOS Websites

QSOS Website is based on the Drakkr^[Visit <http://www.drakkr.org> for further details] project Web infrastructure:

* News and static pages: based on the open source blogging framework _Jekyll Boostrap_^[<http://jekyllbootstrap.com>].

* edoc pages: based on the open source Wiki _Gitit_^[<http://gitit.net>].

### News and Static pages (Jekyll Boostrap)

Jekyll Bootstrap is a lean blogging framework where content is authored in HTML and/or Markdown. It is based on the _Jekyll_^[<http://jekyllrb.com>] framework, used by [GitHub Pages](http://pages.github.com/).

QSOS static website is hosted by GitHub. It means that it's contents are managed in a dedicated branch of the Git repository: [gh-pages](https://github.com/drakkr/QSOS/tree/gh-pages). 

When something new is pushed in this branch, GitHub will use Jekyll to regenerate QSOS static website.

TODO: DNS tricks and CNAME file.

### Edoc pages (Gitit)

Drakkr's Gitit is deployed with git as a backend for the pages.

TODO: complete with repo structure and description on how to export edocs as pages.
