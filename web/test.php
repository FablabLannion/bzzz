<?php
require_once("passwords.php");
require_once("loader.php");

//$toto = isMassAlertRequired($sql,301,$api_login,$api_password);
//echo "test done";

$testSensonet = array (
  'probeId' => '+33685024137',
  'sensorType' => 'LUM',
  'sensorValue' => '33',
  'sensorTimeStamp' => '2013-11-05T23:16:16.0+01:00',
  );
    
//$test = addDataSensonet($sql,$testSensonet ,$api_login,$api_password);

/*echo "Test getRucheIds:";
$test = getRucheIds ($sql,"1");
print_r($test);
echo("\n **** \n");

echo "Test getData:";
$test = getData ($sql,"+33685024137", _ALERT_LUM);
print_r($test);
echo("\n **** \n");
*/
$test = formatSensonetDate("2013-11-05T23:16:16.0+01:00");
echo $test;

?>

