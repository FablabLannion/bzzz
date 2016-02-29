<?php

class sqlConnector {

    private $dbhandle;
    private $db;

    public function __construct() {
    	$this->dbhandle = mysql_connect(_DB_HOST, _DB_USERNAME, _DB_PWD);
        if (!$this->dbhandle) {
            die("Unable to connect to MySQL Database");
        }

	    $this->db = mysql_select_db(_DB_NAME);
        if (!$this->db) {
            die("Unable to Select MySQL Database "._DB_NAME);
        }

	    mysql_query("SET NAMES 'utf8'", $this->dbhandle);
 	    mysql_query("SET CHARACTER SET 'utf8'", $this->dbhandle);
    }

    public function __destruct() {
        if ($this->dbhandle) {
            mysql_close($this->dbhandle);
        }
    }
    
    public function addContact($name, $address, $idLocation, $mobile) {
        $index = 1;
        $dbquery = "SELECT * FROM "._DB_CONTACT_TABLE_NAME
        		." WHERE `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_MOBILE_FIELD."` = '".$mobile."'";
        $dbresultList = $this->sqlQuery($dbquery);
        if ($dbresultList) {
            $index += sizeof($dbresultList);
        }
        
        // codeId = mobile + + index (ex: 06...YYMMDD1)
        $codeId = $mobile.$index;
        $dbquery = "INSERT INTO `"._DB_CONTACT_TABLE_NAME
                    ."` (`"._DB_CONTACT_NAME_FIELD."`, `"._DB_CONTACT_ADDRESS_FIELD."`, `"._DB_CONTACT_IDLOCATION_FIELD
                    ."`, `"._DB_CONTACT_MOBILE_FIELD."`, `"._DB_CONTACT_CODEID_FIELD."`) "
                    ."VALUES('".$name."','"
                                .$address."', '".$idLocation."', '".$mobile."', '".$codeId."')";

        $this->sqlQuery($dbquery);

        return $this->getContactId($firstname, $lastname, $address, $idLocation, $mobile);
    }
    
    public function addNotification($smsSent, $contactId, $notification) {
        // do not add if already exists (possible when web page is updated)
        $dbquery = "SELECT * FROM "._DB_NOTIFICATION_TABLE_NAME
        		." WHERE `"._DB_NOTIFICATION_TABLE_NAME."`.`"._DB_NOTIFICATION_IDCONTACT_FIELD."` = '".$contactId."'"
        		." AND `"._DB_NOTIFICATION_TABLE_NAME."`.`"._DB_NOTIFICATION_MSG_FIELD."` = '".$notification."'"
        		." AND `"._DB_NOTIFICATION_TABLE_NAME."`.`"._DB_NOTIFICATION_CREATIONDATE_FIELD."` = current_date()";

        $dbresultList = $this->sqlQuery($dbquery);

        if (!$dbresultList || !$this->getNext($dbresultList)) {
            $sendingDate = "NULL";
            if ($smsSent) {
                $sendingDate = "current_date()";
            }
            $dbquery = "INSERT INTO `"._DB_NOTIFICATION_TABLE_NAME
                    ."` (`"._DB_NOTIFICATION_IDCONTACT_FIELD."`, `"._DB_NOTIFICATION_CREATIONDATE_FIELD
                    ."`, `"._DB_NOTIFICATION_SENDINGDATE_FIELD."`, `"._DB_NOTIFICATION_MSG_FIELD."`) "
                    ."VALUES('".$contactId."', current_date(), ".$sendingDate.", '".$notification."')";

            $this->sqlQuery($dbquery);
        }
    }

    public function checkPassword($user, $pwd) {
        $dbquery = "SELECT * FROM "._DB_PASSWORD_TABLE_NAME
		." WHERE `"._DB_PASSWORD_TABLE_NAME."`.`"._DB_PASSWORD_USER_FIELD."` = '".$user."'"
		." AND `"._DB_PASSWORD_TABLE_NAME."`.`"._DB_PASSWORD_PWD_FIELD."` = PASSWORD('".$pwd."')";

    	$dbresultList = $this->sqlQuery($dbquery);

        return $dbresultList && $this->getNext($dbresultList);
    }

    public function getContact($contactId) {
        $dbquery = "SELECT * FROM "._DB_CONTACT_TABLE_NAME
		." WHERE `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_ID_FIELD."` = '".$contactId."'";
    
        return $this->sqlQuery($dbquery);
    }
    
    public function getContactId($firstname, $lastname, $sex, $dateOfBirth, $address, $idLocation, $mobile) {
        $dbquery = "SELECT * FROM "._DB_CONTACT_TABLE_NAME
		." WHERE `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_NAME_FIELD."` = '".$name."'"
		." AND `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_ADDRESS_FIELD."` = '".$address."'"
		." AND `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_IDLOCATION_FIELD."` = '".$idLocation."'"
		." AND `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_MOBILE_FIELD."` = '".$mobile."'";
        $dbresultList = $this->sqlQuery($dbquery);
        if ($dbresultList) {
	        $dbresult = $this->getNext($dbresultList);
	        if ($dbresult) {
		        return $dbresult[_DB_CONTACT_ID_FIELD];
		    }
		}
        return -1;
    }

    public function getContacts() {
        $dbquery = "SELECT * FROM "._DB_CONTACT_TABLE_NAME
		." ORDER BY `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_NAME_FIELD."`";
    
        return $this->sqlQuery($dbquery);
    }

    public function getLocation($idLocation) {
        $dbquery = "SELECT * FROM "._DB_LOCATION_TABLE_NAME
		." WHERE `"._DB_LOCATION_TABLE_NAME."`.`"._DB_LOCATION_ID_FIELD."` = '".$idLocation."'";
         
        return $this->sqlQuery($dbquery);
    }

    public function getMobile($contactId) {
        $dbquery = "SELECT * FROM "._DB_CONTACT_TABLE_NAME
    		." WHERE `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_ID_FIELD."` = '".$contactId."'";
        $dbresultList = $this->sqlQuery($dbquery);
        if ($dbresultList) {
	        $dbresult = $this->getNext($dbresultList);
	        if ($dbresult) {
		        return $dbresult[_DB_CONTACT_ID_FIELD];
		    }
		}
        return "";
    }

    public function getLocationName($idLocation) {
        $dbquery = "SELECT * FROM "._DB_LOCATION_TABLE_NAME
	    	." WHERE `"._DB_LOCATION_TABLE_NAME."`.`"._DB_LOCATION_ID_FIELD."` = '".$idLocation."'";
         
        $dbresultList = $this->sqlQuery($dbquery);
        if ($dbresultList) {
	        $dbresult = $this->getNext($dbresultList);
	        if ($dbresult) {
		        return $dbresult[_DB_LOCATION_NAME_FIELD];
		    }
		}
        return "unknown location";
    }

    public function getLocations() {
        $dbquery = "SELECT * FROM "._DB_LOCATION_TABLE_NAME
		." ORDER BY `"._DB_LOCATION_TABLE_NAME."`.`"._DB_LOCATION_NAME_FIELD."`";
    
        return $this->sqlQuery($dbquery);
    }

    public function getRucheData($contactId) {
        $dbquery = "SELECT * FROM "._DB_DATA_TABLE_NAME
		." WHERE `"._DB_DATA_TABLE_NAME."`.`"._DB_DATA_IDCONTACT_FIELD."` = '".$contactId."'"
		." ORDER BY `"._DB_DATA_TABLE_NAME."`.`"._DB_DATA_DATE_FIELD."`";
    
        return $this->sqlQuery($dbquery);
    }

    public function getNotifiedIdContact($minMonth, $minYear, $maxMonth, $maxYear) {
        $dateMin = $minYear."-".$minMonth."-01";
        $dateMax = $maxYear."-".$maxMonth."-01";
        $dbquery = "SELECT * FROM "._DB_NOTIFICATION_TABLE_NAME
    		." WHERE `"._DB_NOTIFICATION_TABLE_NAME."`.`"._DB_NOTIFICATION_CREATIONDATE_FIELD."` >= '".$dateMin."'"
    		." AND `"._DB_NOTIFICATION_TABLE_NAME."`.`"._DB_NOTIFICATION_CREATIONDATE_FIELD."` < '".$dateMax."'";
                
        return $this->sqlQuery($dbquery);
    }
    
    public function getLastWeight($idContact, $maxMonth, $maxYear) {
        $dateMax = $maxYear."-".$maxMonth."-01";
        $dbquery = "SELECT * FROM "._DB_DATA_TABLE_NAME
    		." WHERE `"._DB_DATA_TABLE_NAME."`.`"._DB_DATA_DATE_FIELD."` < '".$dateMax."'"
    		." AND `"._DB_DATA_TABLE_NAME."`.`"._DB_DATA_IDCONTACT_FIELD."` = '".$idContact."'"
    		." AND `"._DB_DATA_TABLE_NAME."`.`"._DB_DATA_TYPE_FIELD."` = 'MASSE'"
		    ." ORDER BY `"._DB_DATA_TABLE_NAME."`.`"._DB_DATA_DATE_FIELD."` DESC";
                 
        $dbresultList = $this->sqlQuery($dbquery);
        if ($dbresultList) {
	        $dbresult = $this->getNext($dbresultList);
	        if ($dbresult) {
		        return $dbresult[_DB_DATA_VALUE_FIELD];
		    }
		}
        return 0;
    }
    
    public function modifyContact($contactId, $name, $address, $idLocation, $mobile) {
       $dbquery = "UPDATE `"._DB_CONTACT_TABLE_NAME."`"
                  ." SET `"._DB_CONTACT_NAME_FIELD."` = '".$name
                  ."', `"._DB_CONTACT_ADDRESS_FIELD."` = '".$address
                  ."', `"._DB_CONTACT_IDLOCATION_FIELD."` = '".$idLocation
                  ."', `"._DB_CONTACT_MOBILE_FIELD."` = '".$mobile
                  ."' WHERE `"._DB_CONTACT_TABLE_NAME."`.`"._DB_CONTACT_ID_FIELD."` = '".$contactId."'";
        
       $this->sqlQuery($dbquery);
    }
    
    public function getNotifications() {
        $dbquery =  "SELECT * FROM "._DB_NOTIFICATION_TABLE_NAME;
        return $this->sqlQuery($dbquery);
   }
    
    public function updateNotificationDates($id, $creation, $sending) {
        $dbquery = "UPDATE `"._DB_NOTIFICATION_TABLE_NAME."`"
                 ." SET `"._DB_NOTIFICATION_CREATIONDATE_FIELD."` = '".$creation
                 ."', `"._DB_NOTIFICATION_SENDINGDATE_FIELD."` = '".$sending
                 ."' WHERE `"._DB_NOTIFICATION_TABLE_NAME."`.`"._DB_NOTIFICATION_ID_FIELD."` = '".$id."'";
        $this->sqlQuery($dbquery);
    }
    
     
    public function getData() {
        $dbquery =  "SELECT * FROM "._DB_DATA_TABLE_NAME;
        return $this->sqlQuery($dbquery);
   }
    
    public function updateDataDate($id, $date) {
        $dbquery = "UPDATE `"._DB_DATA_TABLE_NAME."`"
                ." SET `"._DB_DATA_DATE_FIELD."` = '".$date
                ."' WHERE `"._DB_DATA_TABLE_NAME."`.`"._DB_DATA_ID_FIELD."` = '".$id."'";
        $this->sqlQuery($dbquery);
    }
     
    public function getNext($dbarray) {
        return mysql_fetch_array($dbarray);
    }

    public function sqlQuery($dbquery) {
        $dbresult = mysql_query($dbquery, $this->dbhandle);
        if (!$dbresult) {
            //die("Unable to perform query '".$dbquery."'");
            print_r("<br /><h3 Unable to perform query '".$dbquery."'/><br />");
        } 
 	return $dbresult;
    }

} 
?>


