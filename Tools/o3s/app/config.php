<?php
/*
**  Copyright (C) 2007-2012 Atos 
**
**  Author: Raphael Semeteys <raphael.semeteys@atos.net>
**
**  This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
**  the Free Software Foundation; either version 2 of the License, or
**  (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
**  but WITHOUT ANY WARRANTY; without even the implied warranty of
**  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**  GNU General Public License for more details.
**
**  You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
**
**
** O3S
** config.php: Configuration file of the O3S Web GUI
**
*/
//Local and web paths to QSOS sheets and templates
$git = "master";
$repo = "../backend/$git/";

//Temp directory, with trailing slash
$temp = "/tmp/";

//Activate/Deactivate OpenDocument exports caching
$cache = "off";

//Skin to use (CSS are stored in skins/ subdirectory)
$skin = "default";

//Export template to use: default value = default
$template = "default";

//Locale to use (locale files are stored in locales/ subdirectory)
$default_lang = "fr"; //Default locale
$supported_lang = array('fr', 'en'); //Supported locale

//Use flashplayer to show MindMap 
$use_flash_mm=true;

$db_host = "localhost";
$db_user = "root";
$db_pwd = "osiris";
$db_db = "o3s";
?>
