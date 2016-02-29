<?php
require_once("passwords.php");
require_once("loader.php");

// callback function from sensonet
//
// this file will be invoked by sensonet
// everytime sensonet receive a new SMS from the BZZZ network probe n,etwork

/*
ex
{
    "id": 44,
    "uri": "http:\/\/projects.emerginov.org\/sensonet\/resources\/networks\/BZZ\/subscriptions\/44",
    "eventUri": "http:\/\/projects.emerginov.org\/sensonet\/resources\/events\/D",
    "eventId": "D",
    "networkUri": "http:\/\/projects.emerginov.org\/sensonet\/resources\/networks\/BZZ",
    "networkId": "BZZ",
    "callbackUrl": "http:\/\/projects.emerginov.org\/Bzzz\/bzzzcb.php",
    "format": "json",
    "creator": "BZZZ1234",
    "creationDate": "2013-11-05T22:14:15.0+01:00",
    "updateDate": "2013-11-05T22:14:15.0+01:00"
}
}*/


//
// get Data from sensonet
// 

$request_data = json_decode(file_get_contents("php://input"),true);


//
// push data into database
//
/*
ex
Data: 
array (
  'eventId' => 'D',
  'probeSensorValue' => 
  array (
    'id' => 7838,
    'uri' => 'http://projects.emerginov.org/sensonet/resources/probes/%2B33685024137/sensors/WEI/values/7838',
    'probeUri' => 'http://projects.emerginov.org/sensonet/resources/probes/%2B33685024137',
    'probeId' => '+33685024137',
    'sensorUri' => 'http://projects.emerginov.org/sensonet/resources/sensors/WEI',
    'sensorId' => 'WEI',
    'probeSensorUri' => 'http://projects.emerginov.org/sensonet/resources/probes/%2B33685024137/sensors/WEI',
    'latitude' => 48.7067,
    'longitude' => -3.35521,
    'timestamp' => '2013-11-05T23:08:55.0+01:00',
    'value' => '29.4',
    'creationDate' => '2013-11-05T23:08:55.0+01:00',
    'updateDate' => '2013-11-05T23:08:55.0+01:00',
  ),
))*/

// => send data 
// probe: +33685024137
// sensorId: LUM
// value: 70
// timestamp: 2013-11-05T22:14:20.0+01:00

    $myData = array (
    "probeId"  =>$request_data['probeSensorValue']['probeId'],
    "sensorType" =>$request_data['probeSensorValue']['sensorId'],
    "sensorValue"   => $request_data['probeSensorValue']['value'],
    "sensorTimeStamp"   => $request_data['probeSensorValue']['timestamp']
);

$data=var_export($myData, true);
$myFile = "./media/eventInbox.txt";
$fh = fopen($myFile, 'a') or die("can't open file");
$stringData = "----------------------------------------\n";
fwrite($fh, $stringData);
$stringData = "Received Event at:" . date("Y-m-d H:i:s")  . "\n";
fwrite($fh, $stringData);
$stringData = "\nData: " . $data . "\n";
fwrite($fh, $stringData);
fclose($fh);


/*
ex
array (
  'probeId' => '+33685024137',
  'sensorType' => 'LUM',
  'sensorValue' => '33',
  'sensorTimeStamp' => '2013-11-05T23:16:16.0+01:00',
  */

$test = addDataSensonet($sql,$myData ,$api_login,$api_password);



?>
