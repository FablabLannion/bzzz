<?

//~ Using command line:
//~ curl -i -X "POST" -k -H "Content-Type: application/x-www-form-urlencoded" -H "Accept: application/json" -d "duration=600" http://projects.emerginov.org/sensonet/resources/probes/%2B33685024137/sleepingtime

// Get localtime and execute only if hour is 6H or 21H
$current_hour = date("H");

if ($current_hour == "21" ){
    $sleep_time = 600;
}else if ($current_hour == "06" ){
    $sleep_time = 60;
}else{
    print $current_hour;
    exit;
}

// Sensor ID
$sensor_id = '%2B33685024137';

// URL
$url = "http://projects.emerginov.org/sensonet/resources/probes/$sensor_id/sleepingtime";
$fields = array('duration' => urlencode($sleep_time));
$fields_string ="";
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

// Options
curl_setopt($ch, CURLOPT_HTTPHEADER,
        array("Content-Type: application/x-www-form-urlencoded",
                "Accept: application/json"
            )); 

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

// Print result
print_r($result);
?>
