<?php
require_once("passwords.php");
require_once("loader.php");

$rucherid=  $_REQUEST["rucherid"];
// update alerte status
setAlarmSystem($sql,$rucherid,$_POST["alertetype"]);
 
// update cause type
setAlarmType($sql,$rucherid,$_POST["cause"]);


?>

