<?php
    function api_req($page, $keyID, $vCode, $kindid, $id) {
    // create curl resource
    $ch = curl_init($page . "?keyID=" . $keyID . "&vCode=" . $vCode . "&" . $kindid . "=" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // $response contains the XML response string from the API call
    $response = curl_exec($ch);
    // If curl_exec() fails/throws an error, the function will return false
    if($response === false){
    // Could add some 404 headers here
    return 'Curl error: ' . curl_error($ch);
    } else {
    $apiInfo = new SimpleXMLElement($response);
    return $apiInfo;
    }
    \curl_close($ch);
    // close curl resource to free up system resources  
    }
    function calc_fuel_time($typeID, $fuel) {
        require 'db_con.php';
        mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
        mysql_select_db($db_name) or die(mysql_error());
        $query = "SELECT `quantity` FROM `invControlTowerResources` WHERE  `controlTowerTypeID` = '$typeID'";
        $result = mysql_query($query);
        $time = $fuel / mysql_result($result, 0);
        return $time;
    }
    function calc_stront_time($typeID, $stront) {
        require 'db_con.php';
        mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
        mysql_select_db($db_name) or die(mysql_error());
        $query = "SELECT `quantity` FROM `invControlTowerResources` WHERE  `controlTowerTypeID` = '$typeID'";
        $result = mysql_query($query);
        $rfTime = $stront / mysql_result($result, 1);
        return $rfTime;
    }
?>