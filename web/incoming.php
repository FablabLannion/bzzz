<?php
/* 
 * This file has automacally been created by Emerginov Project Creation mecanism.
 * The purpose of this file is to provide a sample incoming voice call management.
 * Please edit the file to handle properly your incoming voice calls.
 * You might find many information on how to do so on Emerginov wiki.
 * http://emerginov.ow2.org/xwiki
 */

// Load required configuration & Emerginov Class
require_once("passwords.php");
require_once("loader.php");

// Answer the call
$call->Answer();

$rucheid=  $_REQUEST["rucheid"];
$rucherName = getRucherName ($sql,getRucherId($rucheid));

// Say something in French (Agnes is french)
$call->Say("Alerte sur la ruche ".$rucheid." du rucher ".$rucherName." detectée !",$api_login,$api_password,"Agnes");


// Hangup the call
$call->Hangup();
?>
