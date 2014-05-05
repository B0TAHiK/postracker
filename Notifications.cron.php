<?php
    //Requiring some libs...
    require_once 'functions.php';
    require 'db_con.php';
    //Connecting to DB...
    $msg = "Connecting to DB... ";
    mysql_connect($hostname, $username, $mysql_pass);
    if(!mysql_error()) {
        mysql_select_db($db_name);
        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
    } else endlog($msg . mysql_error());
    $query = "SELECT * FROM `users`";
    $result = mysql_query($query);
    //Getting APIs from DB...
    $msg .= "\nCollecting API keys... ";
    $keyIDarr = array();
    $vCodearr = array();
    while($row = mysql_fetch_assoc($result)){
        $keyIDarr[] = $row[keyID];
        $vCodearr[] = $row[vCode];
        $characterIDarr[] = $row[characterID]; 
        $corporationIDarr[] = $row[corporationID];
        $allianceIDarr[] = $row[allianceID];               
    }
    if(count($keyIDarr) > 0) $msg .= " found " . count($keyIDarr) . " API keys"; else endlog($msg . " found none");
    $i=0;
    for ($k = 0; $k < count($keyIDarr); $k++) {
        //Getting XML...
        $page = "https://api.eveonline.com/char/Notifications.xml.aspx";
        $api = api_req($page, $keyIDarr[$k], $vCodearr[$k], 'characterID', $characterIDarr[$k], '', '');
        $msg .=  "\nCurrent Time: " . strval($api->currentTime) . " Cached Until: " . strval($api->cachedUntil);
        //Parsing XML...
        $msg .= "\nParsing Notifications for key " . $keyID;
        foreach ($api->result->rowset->row as $row):
        	$rpt = TRUE;
        	$notificationID = strval($row[notificationID]);
        	$msg .= "\nFound Notifications id " . $notificationID . "... ";
        	$typearr = array(37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 75, 76, 77, 78, 79, 80, 86, 87, 88, 93, 94, 95); // http://wiki.eve-id.net/APIv2_Char_Notifications_XML
        	for($j = 0; $j < count($typearr); $j++){
        		if(strval($row[typeID])==$typearr[$j]){
        			$rpt = FALSE;
        			$msg .= " Right type " . strval($row[typeID]) . "... ";
        		} 
        	}
        	for($j = 0; $j < count($data); $j++){
        		if($data[$j]['notificationID']==$notificationID){
        			$rpt = TRUE;
        			$msg .= " Notification already exists.";
        		}
        	}
        	if(!$rpt){
        		$msg .= " Add notification... ";
        		$xml = simplexml_load_file("https://api.eveonline.com/char/NotificationTexts.xml.aspx?keyID=" . $keyIDarr[$k] . "&vCode=" . $vCodearr[$k] . "&characterID=" . $characterIDarr[$k] . "&IDs=" . $notificationID, 'SimpleXMLElement', LIBXML_NOCDATA);
				if(!empty($xml)){
    				$RawNotifText = $xml->xpath('/eveapi/result/rowset');
            		$data[$i] = array(
                		'notificationID' => $notificationID,
                		'typeID' => strval($row[typeID]),
                		'senderID' => strval($row[senderID]),
                		'senderName' => strval($row[senderName]),
                		'sentDate' => strval($row[sentDate]),
                		'NotificationText' => (string)$RawNotifText[0]->row,
                		'corporationID' => $corporationIDarr[$k],
                		'allianceID' => $allianceIDarr[$k]
            		);
            		$i++;
            		$msg .= " [ok]";
            	} else $msg .= " [fail]";
            }
        endforeach;
    }
    for($k = 0; $k < count($data); $k++){
    	$msg .= "\nAdd Notifications id " . $data[$k]['notificationID'] . " to DB... ";
    	$query = "SELECT `notificationID` FROM `notifications` WHERE `notificationID`='{$data[$k]['notificationID']}' LIMIT 1";
        $result = mysql_query($query);
        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
        $num = mysql_num_rows($result);
        if($num === 0){
        	$msg .= " Inserting new notification... ";
        	$query = "INSERT INTO `notifications` SET `notificationID` = '{$data[$k]['notificationID']}', `typeID` = '{$data[$k]['typeID']}', `senderID` = '{$data[$k]['senderID']}', `senderName` = '{$data[$k]['senderName']}',
        	 `sentDate` = '{$data[$k]['sentDate']}', `NotificationText` = '{$data[$k]['NotificationText']}', `corporationID` = '{$data[$k]['corporationID']}', `allianceID` = '{$data[$k]['allianceID']}'";
        	$result = mysql_query($query);
        	if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
        } else $msg .= " Notification already exists.";
    }
    endlog($msg);
?>