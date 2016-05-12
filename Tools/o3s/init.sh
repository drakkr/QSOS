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

# WARNING : delete all datas (database and qsos files)

mysql < drop_db.sql
mysql < create_db.sql
rm -rf backend/app/upload/*
rm -rf backend/master
rm -rf backend/incoming
mkdir -p backend/master
mkdir -p backend/incoming
cd backend/master
git init
git commit -a -m "Master init"  --allow-empty
cd ../..
cd backend/incoming
git init
git commit -a -m "Incoming init"  --allow-empty
