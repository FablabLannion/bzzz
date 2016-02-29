<?php

// BUTTON ACTIONS
define("_RECORD_DATA"        , 'Record contact');
define("_ADD_MODIFY_DATA"    , 'Add or modify contact');
define("_BACK_TO_PAGE"       , 'Back to check page...');
define("_NOTIFY"             , 'Send notification');

define("_ALERT_MASSE"        , "WEI");
define("_ALERT_HUMIDITY"     , "HUM");
define("_ALERT_TEMPERATURE"  , "TEMP");
define("_ALERT_SON"          , "SON");
define("_ALERT_LUM"          , "LUM");


#define("_DELTA_ALERT_MASS"   , 2);
define("_DELTA_ALERT_MASS"   , 5000);
define("_DELTA_ALERT_SON"    , 15);

define("ALARM_SMS"             , 0);
define("ALARM_PHONE"           , 1);
define("ALARM_MAIL"            , 2);

define("_GRAPH_VALUES"            , 1000);
define("_MIN_VALUES_FOR_DISPLAY"            , 5);
 


/**
 *  DATABASE
**/

// Database configuration
define("_DB_NAME", $mysql_db_name);
define("_DB_HOST", $mysql_db_server);
define("_DB_USERNAME", $mysql_db_login);
define("_DB_PWD", $mysql_db_password);


// mySQL server access in passwords.php

// PASSWORD TABLE
define("_DB_PASSWORD_TABLE_NAME"  , 'password');
define("_DB_PASSWORD_USER_FIELD"  , 'login');
define("_DB_PASSWORD_PWD_FIELD"   , 'password');

// CONTACT TABLE
define("_DB_CONTACT_TABLE_NAME"     , 'contact');
define("_DB_CONTACT_ID_FIELD"       , 'id');
define("_DB_CONTACT_NAME_FIELD"  	, 'name');
define("_DB_CONTACT_ADDRESS_FIELD"  , 'address');
define("_DB_CONTACT_MOBILE_FIELD"   , 'mobile');
define("_DB_CONTACT_MAIL_FIELD"   , 'mail');
define("_DB_CONTACT_USER_FIELD"  , 'login');
define("_DB_CONTACT_PWD_FIELD"   , 'password');
define("_DB_CONTACT_ADMIN_FIELD"   , 'admin');


// LOCATION TABLE
define("_DB_LOCATION_TABLE_NAME", 'location');
define("_DB_LOCATION_ID_FIELD"  , 'id');
define("_DB_LOCATION_PLACE_FIELD", 'place');
define("_DB_LOCATION_LAT_FIELD" , 'latitude');
define("_DB_LOCATION_LONG_FIELD", 'longitude');

// NOTIFICATION TABLE
define("_DB_NOTIFICATION_TABLE_NAME"        , 'notification');
define("_DB_NOTIFICATION_ID_FIELD"          , 'id');
define("_DB_NOTIFICATION_CREATIONDATE_FIELD", 'creation_date');
define("_DB_NOTIFICATION_SENDINGDATE_FIELD" , 'sending_date');
define("_DB_NOTIFICATION_MSG_FIELD"         , 'message');
define("_DB_NOTIFICATION_IDCONTACT_FIELD"   , 'contact_id');

// RUCHER TABLE
define("_DB_RUCHER_TABLE_NAME"        , 'rucher');
define("_DB_RUCHER_ID_FIELD"          , 'rucher_id');
define("_DB_RUCHER_LOCATION_ID_FIELD", 'location_id');
define("_DB_RUCHER_CONTACT_ID_FIELD", 'contact_id');
define("_DB_RUCHER_CREATIONDATE_FIELD", 'creation_date');
define("_DB_RUCHER_NAME_FIELD" 		  , 'name');
	
// RUCHE TABLE
define("_DB_RUCHE_TABLE_NAME"        , 'ruche');
define("_DB_RUCHE_ID_FIELD"          , 'rucheid');
define("_DB_RUCHE_CREATIONDATE_FIELD", 'creation_date');
define("_DB_RUCHE_INFO_FIELD" , 'info');
define("_DB_RUCHE_RUCHER_ID_FIELD" , 'rucher_id');


// RUCHE DATA TABLE
define("_DB_RUCHE_DATA_TABLE_NAME"     , 'ruche_data');
define("_DB_RUCHE_DATA_ID_FIELD"       , 'id');
define("_DB_RUCHE_DATA_VALUE_FIELD"    , 'value');
define("_DB_RUCHE_DATA_DATE_FIELD"     , 'date');
define("_DB_RUCHE_DATA_TYPE_FIELD"     , 'type');
define("_DB_RUCHE_DATA_IDCONTACT_FIELD", 'ruche_rucheid');


?>

