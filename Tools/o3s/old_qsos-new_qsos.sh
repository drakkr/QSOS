#!/bin/bash
#
#Copyright: 2016 Atos
#Author: Julien HEYMAN <julien.heyman@atos.net>
#
#License:
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
#

# update old qsos files, import and validate them.
# put old qsos files into app/backend/app/upload

find app/backend/app/upload -name "*.qsos" -exec xsltproc -o {} formats/xml/xslt/old_qsos-new_qsos.xsl {} \;
find app/backend/app/upload -name "*.qsos" -exec sed -i 's/<authors\/>/<authors><author><name\/><email\/><\/author><\/authors>/g' {} \;
find app/backend/app/upload -name "*.qsos" -exec sed -i '/<include/d' {} \;
find app/backend/app/upload -name "*.qsos" -exec sed -i '/<desc>Description du groupe de critères<\/desc>/d' {} \;
cd app/backend/app/
php massupload.php
php massvalidate.php

