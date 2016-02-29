<?php
  // Include passwords.php to get back Nusery password & login
  require_once("passwords.php");
  // Include Emerginov.php library
  require_once("Emerginov.php");
  
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

This file contains miscelanous functions that can be usefull for something

*/

/*
 * Will return true or false if number given is international or not
 */
function validateInternationalPhoneNumber($number){
    if( preg_match("/^\+[0-9]+$/i", $number) ) {
        return true;
    }
    else return false;
}

/*
 * Will return true or false if string given is a valid mail address
 */
function validateMailAddress($string){
    if( preg_match("/^.+@.+\..+$/i", $string) ) {
        return true;
    }
    else return false;
}

/*
 * Will print the text as an error
 */
function error($text){
    echo "<p class='error'>$text</p>";
}

function info($text){
    echo "<p class='info'>$text</p>";
}

function warning($text){
    echo "<p class='warning'>$text</p>";
}

function debug($text){
    if ('_DEBUG'){
        echo "<pre><p class='debug'>";
        print_r($text);
        echo "</p></pre>";
    }
}

/**
 * StartsWith
 * Tests if a text starts with an given string.
 *
 * @param     string
 * @param     string
 * @return    bool
 */
function StartsWith($Haystack, $Needle){
    // Recommended version, using strpos
    return strpos($Haystack, $Needle) === 0;
}


/*
 * Function to detect Internet Explorer
 * return true if ie, false elsewhere
 * 
 */
function detect_ie(){
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}

/*
* Select Voice
* input: the phone number
* 
* return the $voice 
*  Zozo for French
*  Elizabeth for English
*/    
function selectVoice($sender){
	return "Agnes";
}

function checkMobile($number){
	
	if (	StartsWith($number,"06")	|| 	// France
			StartsWith($number,"07")	) {
				return true;
			} else {
				return false;
			} 
}

/*
 * Escape only single quote in order to be print in HTML code
 * 
 * Inputs: 
 *      $string is the string to input
 * 
 * Outputs
 *      Return the string escaped
 */
function escapeSingleQuote($string){
    return str_replace("'","&#39;",$string);
}

// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
/*
 * 
 * Functions créées pour Bzzz
 *  
 * copyright M.Richomme (fablab Lannion)
 * Avril 2013
 * 
 */
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************

/* 
 * look into the database if the user exists or not
 * 
 */
function doesUserExist($sql,$number){
	// SELECT * FROM `user` WHERE `phone` = +33296052106
	$request = "SELECT * FROM `user` WHERE `phone` = ".$number;
	$res = $sql->fetchAssoc($sql->doQuery($request));
	
	if (sizeof($res) > 0 ){
		return true;
	} else {
		return false;
	}
}

/*
 * look into the database if the ruche exists or not
 * 
 */
function doesRucheExist($sql,$rucheId){
	// SELECT * FROM `ruche` WHERE `rucheid` = 302
	$request = "SELECT * FROM `ruche` WHERE `rucheid` = ".$rucheId;
	$res = $sql->fetchAssoc($sql->doQuery($request));
	
	if (sizeof($res) > 0 ){
		return true;
	} else {
		return false;
	}
}

/*
 * look into the database if the rucher exists or not
 * 
 */
function doesRucherExist($sql,$rucherId){
	// SELECT * FROM `rucher` WHERE `rucher_id` = 8
	$request = "SELECT * FROM `rucher` WHERE `rucher_id` = ".$rucherId;
	$res = $sql->fetchAssoc($sql->doQuery($request));
	
	if (sizeof($res) > 0 ){
		return true;
	} else {
		return false;
	}
}

/*
 * check Password in the DB
 * Note possible TODO => add Hash MD5 for encrypted storage  
 */
function checkPassword($sql,$user, $pwd) {
        $request = "SELECT * FROM "._DB_CONTACT_TABLE_NAME
		." WHERE `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_USER_FIELD."` = '".$user."'"
		." AND `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_PWD_FIELD."` = PASSWORD('".$pwd."')";

		$res = $sql->fetchAssoc($sql->doQuery($request));
	
	if (sizeof($res) > 0 ){
		return true;
	} else {
		return false;
	}
}


// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
//
// functions de type Get pour récupérer les ruchers , le lieu, ..
//
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************

function getRuchers($sql) {
	$request =  "SELECT `rucher_id`,`name` FROM `rucher`";
	$res = $sql->fetchAssoc($sql->doQuery($request));
	
	return $res;
}

function getLocalite($sql) {
	$request =  "SELECT `id`,`place` FROM `location`";
	$res = $sql->fetchAssoc($sql->doQuery($request));
	
	return $res;
}

function getResponsableRucher($sql) {
	$request =  "SELECT `id`,`name` FROM `contact`";
	$res = $sql->fetchAssoc($sql->doQuery($request));
	
	return $res;
}

function getRucherName ($sql,$rucherid) {
	$dbquery = "SELECT `NAME` FROM "._DB_RUCHER_TABLE_NAME
		." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";
		
	$name =$sql->fetchAssoc($sql->doQuery($dbquery));
	
    return $name[0]['NAME'];
}

function getContact($sql,$rucherid) {
        
        $dbquery = "SELECT `contact_id` FROM "._DB_RUCHER_TABLE_NAME
		." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";
				
		$contactid =$sql->fetchAssoc($sql->doQuery($dbquery));
		
		// get Contact name 
		$dbquery = "SELECT `name` FROM "._DB_CONTACT_TABLE_NAME
		." WHERE `"._DB_CONTACT_ID_FIELD."` = '".$contactid[0]['contact_id']."'";
		$contact = $sql->fetchAssoc($sql->doQuery($dbquery));
	
      return $contact[0]['name'];
    }
    
function getLocation($sql,$rucherid) {
	
	    $dbquery = "SELECT `location_id` FROM "._DB_RUCHER_TABLE_NAME
		." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";
		$locationid = $sql->fetchAssoc($sql->doQuery($dbquery));
						
		// get Location name 
		$dbquery = "SELECT `place` FROM "._DB_LOCATION_TABLE_NAME
		." WHERE `"._DB_CONTACT_ID_FIELD."` = '".$locationid[0]['location_id']."'";
	
		$place = $sql->fetchAssoc($sql->doQuery($dbquery));
	
        return $place[0]['place'];
}

function getNbRuches($sql,$rucherid) {
	 $dbquery = "SELECT `rucheid` FROM "._DB_RUCHE_TABLE_NAME
		." WHERE `"._DB_RUCHE_RUCHER_ID_FIELD."` = '".$rucherid."'";
	$ruches =  $sql->fetchAssoc($sql->doQuery($dbquery));
	
	return sizeof($ruches);
}
   
   
function getRucheIds ($sql,$rucherid) {
	$dbquery = "SELECT `rucheid` FROM "._DB_RUCHE_TABLE_NAME
		." WHERE `"._DB_RUCHE_RUCHER_ID_FIELD."` = '".$rucherid."'";
	$ruches =  $sql->fetchAssoc($sql->doQuery($dbquery));
	
	return $ruches;	
}    

function getData ($sql,$rucheid,$type) {
	$dbquery = "SELECT `"._DB_RUCHE_DATA_DATE_FIELD."`,`"._DB_RUCHE_DATA_VALUE_FIELD."` FROM "._DB_RUCHE_DATA_TABLE_NAME
		." WHERE `"._DB_RUCHE_DATA_IDCONTACT_FIELD."` = '".$rucheid."' AND `"._DB_RUCHE_DATA_TYPE_FIELD."` ='".$type."' ORDER BY `date` DESC LIMIT "._GRAPH_VALUES;
	
	$data =  $sql->fetchAssoc($sql->doQuery($dbquery));
		
	return $data;	
}

function getCoordinates($sql,$rucherid) {
	
	$dbquery = "SELECT `location_id` FROM "._DB_RUCHER_TABLE_NAME
	." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";
	$locationid = $sql->fetchAssoc($sql->doQuery($dbquery));
					
	// get Location name 
	$dbquery = "SELECT `longitude` FROM "._DB_LOCATION_TABLE_NAME
	." WHERE `"._DB_CONTACT_ID_FIELD."` = '".$locationid[0]['location_id']."'";
	$lon = $sql->fetchAssoc($sql->doQuery($dbquery));

	// get Location name 
	$dbquery = "SELECT `latitude` FROM "._DB_LOCATION_TABLE_NAME
	." WHERE `"._DB_CONTACT_ID_FIELD."` = '".$locationid[0]['location_id']."'";
	$lat = $sql->fetchAssoc($sql->doQuery($dbquery));
	
	$array = array(
		"latitude" =>  $lat[0]['latitude'],
		"longitude" => $lon[0]['longitude'],
	);		

	return $array; 
}

function getRucherId($ruche_id){
	// système de numérotation des ruches
	// 1407 => ruche 7 du rucher 14	
	return substr($ruche_id,0,-2);
}

// passage à sensonet
// systeme de nommage rucher/ruche est différent
function getRucherIdSensonet($sql,$ruche_id){
	// SELECT rucher_id FROM `ruche` WHERE rucheid='+33685024137'
	$dbquery = "SELECT `rucher_id` FROM "._DB_RUCHE_TABLE_NAME
	." WHERE `"._DB_RUCHE_ID_FIELD."` = '".$ruche_id."'";
	
	$rucher_id =$sql->fetchAssoc($sql->doQuery($dbquery));
	
	return $rucher_id[0][_DB_RUCHER_ID_FIELD]; 
}



// TODO à vérifier 
function getMobile($sql,$rucher_id){
       $dbquery = "SELECT `contact_id` FROM "._DB_RUCHER_TABLE_NAME
		." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucher_id."'";
				
		$contactid =$sql->fetchAssoc($sql->doQuery($dbquery));
		
		// get Contact name 
		$dbquery = "SELECT `mobile` FROM "._DB_CONTACT_TABLE_NAME
		." WHERE `"._DB_CONTACT_ID_FIELD."` = '".$contactid[0]['contact_id']."'";
		$contact = $sql->fetchAssoc($sql->doQuery($dbquery));
	
      return $contact[0][_DB_CONTACT_MOBILE_FIELD];
}

function getMail($sql,$rucher_id){
       $dbquery = "SELECT `contact_id` FROM "._DB_RUCHER_TABLE_NAME
		." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucher_id."'";
				
		$contactid =$sql->fetchAssoc($sql->doQuery($dbquery));
		
		// get Contact name 
		$dbquery = "SELECT `mail` FROM "._DB_CONTACT_TABLE_NAME
		." WHERE `"._DB_CONTACT_ID_FIELD."` = '".$contactid[0]['contact_id']."'";
		$contact = $sql->fetchAssoc($sql->doQuery($dbquery));
	
      return $contact[0][_DB_CONTACT_MAIL_FIELD];
}

/*
 * function pour retrouver l'ID de la localisation connaissant l'ID du rucher
 */
function getLocationId($sql,$rucherid) {
	$dbquery = "SELECT `location_id` FROM "._DB_RUCHER_TABLE_NAME
		." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";
		
	$rep =$sql->fetchAssoc($sql->doQuery($dbquery));
	
	return $rep[0]['location_id'];	
}

/*
 * function pour retrouver l'ID du responsable en connaissant l'ID du rucher
 */
function getContactId($sql,$rucherid) {
	$dbquery = "SELECT `contact_id` FROM "._DB_RUCHER_TABLE_NAME
		." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";
		
	$rep =$sql->fetchAssoc($sql->doQuery($dbquery));
	
	return $rep[0]['contact_id'];	
}

/*
 * récupère le status de l'alarme en fonction du rucher ID
 * si l'apiculteur intervient sur le rucher il peut desactiver l'alarme via l'application
 */
function getAlarmSystem($sql,$rucherid) {
	
	$request =  "SELECT `alert` FROM `rucher`"
	." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";
	
	echo $request;
	$res = $sql->fetchAssoc($sql->doQuery($request));
		
	 return $res[0]['alert'];
}

function getAlarmType($sql,$rucherid) {

		// récupération du propriétaire du rucher
        $dbquery = "SELECT `contact_id` FROM "._DB_RUCHER_TABLE_NAME
		." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";
				
		$contactid =$sql->fetchAssoc($sql->doQuery($dbquery));
		
		// récupérer son système de notification
		$dbquery = "SELECT `alert_type` FROM "._DB_CONTACT_TABLE_NAME
		." WHERE `"._DB_CONTACT_ID_FIELD."` = '".$contactid[0]['contact_id']."'";
		$contact = $sql->fetchAssoc($sql->doQuery($dbquery));
	
      return $contact[0]['alert_type'];

}


// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
//
// functions qui ajoutent, crééent ou modifient des éléments ne base de données
//
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************

function createUser($sql,$number){
	$request = "INSERT INTO `"._DB_NAME."`.`user` (`subscription`, `phone`) VALUES ( CURRENT_TIMESTAMP,'".$number."');";	
	$res = $sql->doQuery($request);

	return $res;
}

function addRucher($sql,$nom_rucher,$localiteid,$resp_rucherid) {
	$request = "INSERT INTO `rucher` (`location_id`, `contact_id`,`name`) VALUES ('".$localiteid."','".$resp_rucherid."','".$nom_rucher."');";	
	$res = $sql->doQuery($request);	
	
	return $res;
}

function addLocation($sql,$place,$longitude,$latitude) {
	$request = "INSERT INTO `"._DB_NAME."`.`location` (`place`, `longitude`, `latitude`) VALUES ( '".$place."','".$longitude."','".$latitude."');";	
	$res = $sql->doQuery($request);

	return $res;
}

function addContact($sql,$name,$address,$phone,$mail,$alerte) {
	$txt ="";

	// check number
	if (checkMobile($phone) ) {
		$request = "INSERT INTO `"._DB_NAME."`.`contact` (`name`, `address`, `mobile`,`mail`,`alert_type`) VALUES ( '".$name."','".$address."','".$phone."','".$mail."','".$alerte."');";	
		
		print_r($request);
		
		$res = $sql->doQuery($request);
		if (!$res) {
			$txt = error("Erreur, contactez l'administrateur");			
		} else {
			$txt = "Contact enregistré";
		}
	} else {
		$txt = error("Format du téléphone incorrect (mobile requis)");
	}
	
	return $txt;
}

/*
 * Ajouter une ruche à un rucher
 * 
 * */
function addRuche($sql,$rucheid,$info,$rucherid) {

	// check rucherid existe
	if (doesRucherExist($sql,$rucherid)){
	
		// rucheid = 100*rucherId + rucheId
		// e.g. rucher 3
		// utilisateur créé la ruche 4
		// => rucheId (unique) = 3*100 +4 =304
		// toutes les ruches du rucher 3 seront de 301 à 399
		//99 ruches max par rucher
		$rucherid_unique = 100*$rucherid + $rucheid;
		
		$request = "INSERT INTO `"._DB_NAME."`.`ruche` (`rucheid`, `info`, `rucher_id`,`creation_date`) VALUES ( '".$rucherid_unique."','".$info."','".$rucherid."',NOW());";	
		
		$sql->doQuery($request);
		
		// TODO message d'erreur		 
		$res = "Ruche enregistrée";
	} else {
		$res = error("Rucher inconnu. Impossible d'ajouter la ruche");
	}
	return $res;
}

/*
 * function to check if for a given category there is enough values for display 
 * _MIN_VALUES_FOR_DISPLAY
 */
function hasData($sql,$rucheid,$datatype) {
	
	$data = getData($sql,$rucheid,$datatype);
		
	if ( sizeof($data) > _MIN_VALUES_FOR_DISPLAY) {
		return true;
	} else {
		return false;
	}
}

/*
 * Ajout de data du capteur
 *  
 * TODO à voir avec sensonet
 */
function addData($sql,$data,$api_login,$api_password) {
	
	$text = "";
	
	// SMS
	// "1234 45.2 13 3.12"
	// 
	$ruche_id = $data[0]; // 1234 => ID de la ruche


	// entre ruches Bzz et ruche Le Dantec => pas les mêmes params
	// pour être générique possibilité de bypasser un param
	// si param=-1 on ne renseigne pas en base
	// mettre 0??
	$rucherid= getRucherId($ruche_id);
		
	// Check que l'ID de la ruche est déclaré
	if (doesRucheExist($sql,$ruche_id)) {
		
		if (getAlarmSystem($sql,$rucherid) == "ON"){
				
			// Masse
			if (sizeof($data) > 1){
				if ($data[1] != "-" ) {
					$request = "INSERT INTO `"._DB_NAME."`.`ruche_data` (`value`, `type`,`ruche_rucheid`) VALUES ( '".$data[1]."','"._ALERT_MASSE."','".$ruche_id."');";	
					$res = $sql->doQuery($request);
					
					// check si le delta de masse justifie la notification d'une alarme à l'apiculteur
					isMassAlertRequired($sql,$ruche_id,$api_login,$api_password);
				}
			}

			// Température
			if (sizeof($data) > 2){	
				if ($data[2] != "-" ) {					
					$request = "INSERT INTO `"._DB_NAME."`.`ruche_data` (`value`, `type`,`ruche_rucheid`) VALUES ( '".$data[2]."','"._ALERT_TEMPERATURE."','".$ruche_id."');";	
					$res = $sql->doQuery($request);
				}
			}
			
			// Humidité
			if (sizeof($data) > 3){
				if ($data[3] != "-" ) {					
					$request = "INSERT INTO `"._DB_NAME."`.`ruche_data` (`value`, `type`,`ruche_rucheid`) VALUES ( '".$data[3]."','"._ALERT_HUMIDITY."','".$ruche_id."');";	
					$res = $sql->doQuery($request);
				}
			}
			
			// Son
			if (sizeof($data) > 4){
				if ($data[4] != "-" ) {						
					$request = "INSERT INTO `"._DB_NAME."`.`ruche_data` (`value`, `type`,`ruche_rucheid`) VALUES ( '".$data[4]."','"._ALERT_SON."','".$ruche_id."');";	
					$res = $sql->doQuery($request);
				}
			}
			
		$text = "Data enregistrées";
			

		} else {
			$text = "Système d'alarme inactif.";
		}
	} else {
		$text = error("Ruche inconnue");	
	}
	// TODO gestion des erreurs
	
	return $text;
}

function setAlarmSystem($sql,$rucherid,$status) {
	// UPDATE `proj_Bzzz_pmeqbg5r`.`rucher` SET `alert` = 'ON' WHERE `rucher`.`rucher_id` =1 AND `rucher`.`location_id` =7 AND `rucher`.`contact_id` =18 LIMIT 1 ;
	$req = "UPDATE `"._DB_NAME."`.`rucher` SET `alert`='".$status."' WHERE `rucher_id` =".$rucherid." LIMIT 1";	
	$res = $sql->doQuery($req);
}

function setAlarmType($sql,$rucherid,$alarmType) {

	// récupération du propriétaire du rucher
    $dbquery = "SELECT `contact_id` FROM "._DB_RUCHER_TABLE_NAME
	." WHERE `"._DB_RUCHER_ID_FIELD."` = '".$rucherid."'";

	$contactid =$sql->fetchAssoc($sql->doQuery($dbquery));
			
	//UPDATE `proj_Bzzz_pmeqbg5r`.`contact` SET `alert_type` = 'SMS' WHERE `contact`.`id` =18 LIMIT 1 ;
	$req = "UPDATE `"._DB_NAME."`.`contact` SET `alert_type`='".$alarmType."' WHERE `contact`.`id` =".$contactid[0]['contact_id']." LIMIT 1";			
	$res = $sql->doQuery($req);		
}

/* updateRucher
 * 
 * cette function permet de modifier les informations d'un rucher existant
 * on peut modifier
 * - le nom
 * - le nom du responsable
 * - la localisation
 * - le lieu
 * 
 * */
function updateRucher($sql,$rucherid,$nom_rucher,$localite,$lat,$long,$responsable) {
// rucherId
// => locationID
// => contactId
$contactId = getContactId($sql,$rucherid);
$text = "";
// update 2 tables
// - rucher
// - localisation
// Rucher nom_rucher, contactId << responsable, localiteID
		$req = "UPDATE `"._DB_NAME."`.`rucher` SET `name`='".$nom_rucher."',`location_id`='".$localite."',`contact_id`='".$contactId."' WHERE `rucher_id` =".$rucherid." LIMIT 1";	
		$res = $sql->doQuery($req);

// location $localite,$lat,$long
// UPDATE `proj_Bzzz_pmeqbg5r`.`location` SET `place` = 'Pays des schtroumpfs' WHERE `location`.`id` =8 LIMIT 1 ;
		$req = "UPDATE `"._DB_NAME."`.`location` SET `latitude`='".$lat."',`longitude`='".$long."' WHERE `location`.`id` =".$localite." LIMIT 1";	
		$res = $sql->doQuery($req);

	$text = "Modifications enregistrées.";

	return $text;
}

// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************


function isMassAlertRequired($sql,$rucheid,$api_login,$api_password) {

	// compare last data with previous data
	// if delta_alert (2 kg) & flag alert (0 alert / 1 no alert (work in progress)) => alert
	// récupération des 5 dernières valeurs: N1, N2, N3, N4, N5
	// |N4-N5| < 0.2
	// si |N5-Ni| > _DELTA_ALERT_MASS (i=1,2,3) genère une notification
	// la difficulté .... éviter le spam...
	//
	//
	$rucherid= getRucherId($rucheid);
	$type="MASSE"; // TODO generalisation / autres valeurs
	$alarmFlag = getAlarmSystem($sql,$rucherid);
	$sendAlarmFlag=false;
	
//	echo "alarm=".$sendAlarmFlag;
//	echo " <br />";
	
	$req = "SELECT `"._DB_RUCHE_DATA_DATE_FIELD."`,`"._DB_RUCHE_DATA_VALUE_FIELD."` FROM "._DB_RUCHE_DATA_TABLE_NAME
	." WHERE `"._DB_RUCHE_DATA_IDCONTACT_FIELD."` = '".$rucheid."' AND `"._DB_RUCHE_DATA_TYPE_FIELD."` ='".$type."' ORDER BY `date` DESC LIMIT 5";
	$data =  $sql->fetchAssoc($sql->doQuery($req));
	
	//print_r($data);
	
/*	ex
 * 
 *  Array ( 
	 [0] => Array ( [date] => 2013-04-22 16:44:36 [value] => 64 ) 
	 [1] => Array ( [date] => 2013-04-11 22:31:27 [value] => 65.8 ) 
	 [2] => Array ( [date] => 2013-04-10 22:56:09 [value] => 63 ) 
	 [3] => Array ( [date] => 2013-04-08 23:08:36 [value] => 65.8 ) 
	 [4] => Array ( [date] => 2013-04-08 22:35:22 [value] => 65.8 ) ) 
*/	
	
	if (sizeof($data) > 4 ) {		
		if ( abs($data[0]['value'] -$data[1]['value']) < 0.2 
			&& abs($data[0]['value'] -$data[2]['value']) > _DELTA_ALERT_MASS 
			&& abs($data[0]['value'] -$data[3]['value']) > _DELTA_ALERT_MASS
			&& abs($data[0]['value'] -$data[4]['value']) > _DELTA_ALERT_MASS ) {
			$sendAlarmFlag =true;
		}
	}

	if ($alarmFlag == "ON" && $sendAlarmFlag) {

// echo "alarm set";

		$alarmType = getAlarmType($sql,$rucherid);
		$rucher_name = getRucherName($sql,$rucherid);
		$msg = "Alerte sur la ruche ".$rucheid." du rucher ".$rucher_name;
			
		// TODO
		// create checkNotif
		// ne pas notifier si on l' a fait... récemment....	
		//
		if ($alarmType == "SMS" ) {
			// send SMS
			$number = getMobile($sql,$rucherid);
			
			// Get a new Emerginov object
			$Emerginov = new Emerginov($api_login, $api_password);
		 
			// Send the SMS
			$res = $Emerginov->SendSMS($number, $msg);
		  
			// If success
			if ($res->Success == True){
				echo "The request has been done!";
			 } else{
				echo $res->Result;
			 }
			
		}else if ($alarmType == "MAIL" ) {
			// send Mail
			$mail = getMail($sql,$rucherid);
			
			$subject = "Alerte Bzzz sur ruche ".$rucheid."!";
			$headers = "";
			$headers .= "From: Bzzz\n";
			$headers .= "MIME-version: 1.0\n";
			$headers .= "Content-Type: text/plain; charset=utf-8\n"; 

			echo $mail;
			
			mail($mail, $subject, $msg, $headers);
			
		}else if ($alarmType == "PHONE" ) {
			// generate Call
			$number = getMobile($sql,$rucherid);
			$Emerginov = new Emerginov($api_login, $api_password);
 
			$ret = $Emerginov->Call($number, "incoming.php?rucheid=".$rucheid);
		}
		
		// create notification à mettre dans la base
		createNotification($sql,$rucheid,$alarmType,$msg);
	}

}


function createNotification($sql,$rucheid,$alarmType,$msg) {

	$contact_id = getContactId($sql,getRucherId($rucheid));
	
	$req = "INSERT INTO `"._DB_NAME."`.`notification` (`message`, `contact_id`,`notifType`) VALUES ( '".$msg."','".$contact_id."','".$alarmType."');";	
	$res = $sql->doQuery($req);
}

// *************************************************************************
//
// Sensonet
/*
 * Ajout de data du capteur
 *  
 * data = [
  probeID: +33685024137
  sensorId: LUM
  value: 70
  timestamp: 2013-11-05T22:14:20.0+01:00
 */
function addDataSensonet($sql,$data,$api_login,$api_password) {
        
	$text = "";
    $type=$data['sensorType'];
    $value=$data['sensorValue'];
    $dataTimeStamp=$data['sensorTimeStamp'];
    
/*    echo "type=".$type;
    echo "value=".$value;
    echo "timestamp=".$dataTimeStamp;
*/    
	
	// get RucheID from probeId
	$ruche_id = $data['probeId']; //  ID de la ruche => No SIM TODO à revoir pour les ruchers
//	echo "rucheId=".$ruche_id;

	// entre ruches Bzz et ruche Le Dantec => pas les mêmes params
	// pour être générique possibilité de bypasser un param
	// si param=-1 on ne renseigne pas en base
	// mettre 0??
	$rucherid= getRucherIdSensonet($sql,$ruche_id);
		
	// Check que l'ID de la ruche est déclaré
	if (doesRucheExist($sql,$ruche_id)) {
	
//		echo "Rucher existe";
		
		if (getAlarmSystem($sql,$rucherid) == "ON"){
			
			
//			echo "Système d'alarme actif";
			
            // TODO add timestamp in requests
            $date_sensonet = formatSensonetDate($dataTimeStamp); 
            
			// Masse
			if ($type == _ALERT_MASSE ){
					$request = "INSERT INTO `"._DB_NAME."`.`ruche_data` (`value`, `type`,`ruche_rucheid`,`date`) VALUES ( '".$value."','"._ALERT_MASSE."','".$ruche_id."','".$date_sensonet."');";	
					$res = $sql->doQuery($request);
					// check si le delta de masse justifie la notification d'une alarme à l'apiculteur
					isMassAlertRequired($sql,$ruche_id,$api_login,$api_password);
			} else if ($type == _ALERT_TEMPERATURE ){ // Température	
					$request = "INSERT INTO `"._DB_NAME."`.`ruche_data` (`value`, `type`,`ruche_rucheid`,`date`) VALUES ( '".$value."','"._ALERT_TEMPERATURE."','".$ruche_id."','".$date_sensonet."');";	
					$res = $sql->doQuery($request);
			} else if ($type == _ALERT_LUM ){			
					$request = "INSERT INTO `"._DB_NAME."`.`ruche_data` (`value`, `type`,`ruche_rucheid`,`date`) VALUES ( '".$value."','"._ALERT_LUM."','".$ruche_id."','".$date_sensonet."');";	
					$res = $sql->doQuery($request);
			}
						
		$text = "Data enregistrées";
//		print_r($res);
			

		} else {
			$text = "Système d'alarme inactif.";
		}
	} else {
		$text = error("Ruche inconnue");	
	}
	// TODO gestion des erreurs
	
	return $text;
}

// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
//
// functions utiles pour l'affichage
//
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************
// *************************************************************************

function formatForDisplay($data){
	$display_data ="[";
	for ($i = 0; $i<sizeof($data); $i++) {
		$display_data .="['";
		$display_data .=$data[$i]['date'];
		$display_data .="',";
		$display_data .=$data[$i]['value'];
		$display_data .="],";
	}
	//remove last ,
	$display_data = rtrim($display_data, ',');
	$display_data .="]";	
	return $display_data;
}

function formatSensonetDate($date){
	// 2013-11-05T23:16:16.0+01:00 (sensonet)
	// => 2013-11-05 23:16:16

	$pattern1 = '$2013-[0-9]*-[0-9]*$';
	$pattern2 = '$[0-9]*:[0-9]*:[0-9]*$';

	preg_match_all($pattern1,$date,$sensonet_year, PREG_PATTERN_ORDER);
	preg_match_all($pattern2,$date,$sensonet_time, PREG_PATTERN_ORDER);
		
	$newDate = $sensonet_year[0][0]." ".$sensonet_time[0][0];

	return $newDate;
}


function displayRucheID($id){
	// id de ruche = 10*Id rucher + ID de ruche
	// ex: 714 = 14 ème ruche du rucher 7
	return substr($id, -2);
}    

?>
