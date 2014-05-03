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
    function calc_fuel_time($typeID, $fuel, $systemID, $allyownerID, $msg) {
        require 'db_con.php';
        mysql_connect($hostname, $username, $mysql_pass);
        if(!mysql_error()) {
            mysql_select_db($db_name);
            if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
        } else endlog($msg . mysql_error());
        $query = "SELECT `quantity` FROM `invControlTowerResources` WHERE  `controlTowerTypeID` = '$typeID'";
        $result = mysql_query($query);
        $page = "https://api.eveonline.com/map/Sovereignty.xml.aspx";
        $api = api_req($page, "", "", "", "");
        $systemownerID = $api->xpath("/eveapi/result/rowset/row[@solarSystemID=$systemID]/@allianceID");
        $time = $fuel / (($allyownerID != $systemownerID[0][0]) ? mysql_result($result, 0) : mysql_result($result, 0)*0.75);
        return floor($time);
    }
    function calc_stront_time($typeID, $stront, $allyownerID, $msg) {
        require 'db_con.php';
        mysql_connect($hostname, $username, $mysql_pass);
        if(!mysql_error()) {
            mysql_select_db($db_name);
            if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
        } else endlog($msg . mysql_error());
        $query = "SELECT `quantity` FROM `invControlTowerResources` WHERE  `controlTowerTypeID` = '$typeID'";
        $result = mysql_query($query);
        $page = "https://api.eveonline.com/map/Sovereignty.xml.aspx";
        $api = api_req($page, "", "", "", "");
        $systemownerID = $api->xpath("/eveapi/result/rowset/row[@solarSystemID=$systemID]/@allianceID");
        $rfTime = $stront / (($allyownerID != $systemownerID[0][0]) ? mysql_result($result, 1) : mysql_result($result, 1)*0.75);
        return floor($rfTime);
    }
    function endlog($m) {
        echo $m . "\n[" . str_repeat("=",100) . "]\n";
        exit;
    }
    function xml2array ($xmlObject, $out = array ()){
        foreach ((array) $xmlObject as $index => $node ) {
            $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
        }
        return $out;
    }
    function hoursToDays($inputTime) {
        $hoursInADay = 24;
        $days = floor($inputTime / $hoursInADay);
        $hoursLeft = $inputTime - $days * $hoursInADay;
        $result = array (
            'd' => $days,
            'h' =>$hoursLeft
        );
        return $result;
    }
?>