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
** O3S - mass import
** massupload.php: import qsos files in o3s
**
*/

if(php_sapi_name() != "cli") {
        echo "You can only use this script in command line";
        exit();
}

require("conf.php");
require("upload.inc");

$re1='.*?';
$re2='(\\.)';
$re3='(qsos)';
$re4='(mm)';

$files = scandir('upload/');
foreach ($files as $file) {
	if (is_file('upload/'.$file)){
		$name_file='upload/'.$file;
		if((preg_match_all ("/".$re1.$re2.$re3."/is", $name_file, $matches)) || (preg_match_all ("/".$re1.$re2.$re4."/is", $name_file, $matches))){
			upload(array('tmp_name'=> $name_file,'name'=>$file),"Mass Import",false);
		}
	}
}
?>
