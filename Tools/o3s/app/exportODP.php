<?php
/*
**  Copyright (C) 2012 Atos 
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
** O3S
** exportODP.php: ODP generation
**
*/
session_start();

//Weightings are stored in session
$weights = $_SESSION;
//QSOS evaluations to display
$ids = $_REQUEST['id'];
//Criterion to detail
$name = $_REQUEST['c'];

include("../formats/libs/QSOSComparison.php");
$myComp = new QSOSComparison($ids, $name);
$myComp->ODP();

?> 
