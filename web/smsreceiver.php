<?php
/* 
 * This file has automacally been created by Emerginov Project Creation mecanism.
 * The purpose of this file is to provide a sample SMS receiver management.
 * Please edit the file to handle properly your incoming SMS.
 * You might find many information on how to do so on Emerginov wiki.
 * http://emerginov.ow2.org/xwiki
 * 
 * keyword déclaré: Bz
 * 
 */

// Load required configuration & Emerginov Class
require_once("passwords.php");
require_once("loader.php");

// Get SMS information
// SOA = numéro de la personne/sond qui appelle
// contnent = contenu du SMS
$sender = $_REQUEST["SOA"];
$content = $_REQUEST["Content"];

// What to send back if requested directly thru HTTP:
$HTTPResponseContent = "ok";

// Open a log file
$fh = fopen("sms.log","a");

// Write SMS content to this log file
fwrite($fh, "---- On ".date("r").", I received a SMS from: $sender. Content is: $content\n");

// on a choisit un formalisme du style
// Bz <id de la ruche> <value1> <value2> <value3>
// 
// on part du principe que l'id de ruche est unique
// 2 ruches de 2 ruchers différents ne peuvent pas voir le même id
//
// avec explode on créé un tableau
// si message = Bz 105 26.8 88.8 0.123
// data = [Bz,105,26.8,88.8,0.123]
// on considère jusque 4 valeurs
// val1 = MASSE
// val2 = TEMP
// val3 = HUMIDITE
// val4 = SON
//
// TODO multiplexage de valeur
//
// Bz 101 50*102 - - 4 5*113 37 21 14 65
// => ruche 101: [50kg]
// => ruche 102: [4hum,5son]
// => ruche 113: [113kg,37deg,14hum,65son]
//
// note - = pas de valeur
//
// considérer * comme élément séparateur
// faire N update data (dans l'exemple 3 fois)
//

// remove Bz prefix (if coming from a SMS)
// possible to send a request withou prefix => direct HTTP request
if (StartsWith($content,"Bz")) {
	$content = substr($content,3);
}

// test if multiple values (look for * char)
$pos = strpos($content, "*");
$data = array();
$test = "";

// single value
if ($pos === false) {
	$data = explode(" ", $content);
	
	// add Data in Database
	$test = addData($sql, $data,$api_login,$api_password);
// for multiplexed values
} else {
	// several values
	// e.g 50 4 3 * 42 7 2 1 * 38 9.2 4.1
	// 
	$data_multiple = explode("*", $content);
	
	foreach ($data_multiple as $i ) {
	
		$data = explode(" ", $i);		
		// add Data in Database
		$test = addData($sql, $data,$api_login,$api_password);
	}	
}

// TODO
// Below, we should define a set of error codes to minimize the data volume
// and explicit the reason if an error occure.
// Suggested codes (starting at 600, to avoid confusion with HTTP):
//   - 600 - ok
//   - 601 - no data received
//   - 602 - unknown behive
//   - 603 - unauthorized sender (is it possible through direct HTTP ?)
//   - 611 - bad data format
//   - 612 - bad data value (out of range, negative weight etc.)
//   - ...

if (! $data) {
  $HTTPResponseContent = "nok";
}

// log des données insérées
fwrite($fh, "---- On ".date("r").", data added\n");

// Close the log file
fclose($fh);
// Send something to the requester:
echo $HTTPResponseContent;
?>
