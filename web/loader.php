<?php
/*
    Copyright 2011 Emerginov Team <admin@emerginov.org>
    
    This file is part of Emerginov Scripts.

    This script is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This script is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this script.  If not, see <http://www.gnu.org/licenses/>.

*/
/*

PHP libraries loaders, includes, etc.

*/

// Load all files
require_once('passwords.php');
require_once('util/config.php');
require_once('Emerginov.php');
require_once('includes/miscFunctions.php');
require_once('includes/SqlConnector.class.php');
require_once('includes/Mobile_Detect.php');
require_once('includes/datetime.php');

// Start session
session_start();

$detect = new Mobile_Detect();

// Create an new SQL connector object
$sql = new SqlConnector();

?>
