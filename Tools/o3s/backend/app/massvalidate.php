<?php
/*
** Copyright (C) 2016 Atos 
**
** Author: Julien HEYMAN <julien.heyman@atos.net>
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
**
**
** O3S - Validate all incoming qsos files
** massvalidate.php: validate all incoming qsos files. Used after massupload.php
**
*/

if(php_sapi_name() != "cli") {
        echo "You can only use this script in command line";
        exit();
}

require('conf.php');

$files = scandir('../incoming/');
foreach ($files as $file) {
	if (is_file('../incoming/'.$file)){
		$var=$file;
		$return = $incoming->upgradeEval($var);
		$master->add();
		$incoming->commit("$var was moved !");
		$master->commit("$var was $return !");
	}
}


?>
