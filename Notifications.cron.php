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
    $users = array();
    while($row = mysql_fetch_assoc($result)){
    	$users[] = array(
    		'keyID' => $row[keyID],
        	'vCode' => $row[vCode],
        	'characterID' => $row[characterID],
        	'corporationID' => $row[corporationID],
        	'allianceID' => $row[allianceID],
        	'lastNotifID' => $row[lastNotifID]
    	);
    }
    if(count($users) > 0) $msg .= " found " . count($users) . " API keys"; else endlog($msg . " found none");
    $i=0;
    for ($k = 0; $k < count($users); $k++) {
        //Getting XML...
        $page = "https://api.eveonline.com/char/Notifications.xml.aspx";
        $api = api_req($page, $users[$k][keyID], $users[$k][vCode], 'characterID', $users[$k][characterID], '', '');
        $msg .=  "\nCurrent Time: " . strval($api->currentTime) . " Cached Until: " . strval($api->cachedUntil);
        //Parsing XML...
        $msg .= "\nParsing Notifications for key " . $users[$k][keyID];
        foreach ($api->result->rowset->row as $row):
        	$rpt = FALSE;
        	$rtype = FALSE;
        	$notificationID = strval($row[notificationID]);
        	$msg .= "\nFound Notifications id " . $notificationID . "... ";
        	$typearr = array(37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 75, 76, 77, 78, 79, 80, 86, 87, 88, 93, 94, 95); // http://wiki.eve-id.net/APIv2_Char_Notifications_XML
        	for($j = 0; $j < count($typearr); $j++){
        		if(strval($row[typeID])==$typearr[$j]){
        			$rtype = TRUE;
        			for($h = 0; $h < count($data); $h++){
        				if($data[$h]['notificationID']==$notificationID){
        					$rpt = TRUE;
        					$msg .= " Notification already exists... [fail]";
        					break 2;
        				}
        			}
        		}
        	}
        	if(!$rpt) $msg .= ($rtype) ? " Right type " . strval($row[typeID]) . "... " : " Wrong type " . strval($row[typeID]) . "... [fail]";
        	if(!$rpt && $rtype){
        		$msg .= " Add notification... ";
        		$xml = simplexml_load_file("https://api.eveonline.com/char/NotificationTexts.xml.aspx?keyID=" . $users[$k][keyID] . "&vCode=" . $users[$k][vCode] . "&characterID=" . $users[$k][characterID] . "&IDs=" . $notificationID, 'SimpleXMLElement', LIBXML_NOCDATA);
				if(!empty($xml)){
    				$RawNotifText = $xml->xpath('/eveapi/result/rowset');
            		$data[$i] = array(
                		'notificationID' => $notificationID,
                		'typeID' => strval($row[typeID]),
                		'senderID' => strval($row[senderID]),
                		'senderName' => strval($row[senderName]),
                		'sentDate' => strval($row[sentDate]),
                		'NotificationText' => (string)$RawNotifText[0]->row,
                		'corporationID' => $users[$k][corporationID],
                		'allianceID' => $users[$k][allianceID]
            		);
            		$i++;
            		$msg .= " [ok]";
            	} else $msg .= " [fail]";
            }
        endforeach;
    }
    $msg .= "\nAdd Notifications to DB";
    for($k = 0; $k < count($data); $k++){
    	$msg .= "\nNotifications id " . $data[$k]['notificationID'] . "... ";
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
    /*$msg .= "\nSending e-mails with new notifications";
    for ($k = 0; $k < count($users); $k++){
    	for($j = 0; $j < count($data); $j++){
    		if($data[$j]['notificationID'] > $users[$k][lastNotifID]){
    			$msg .= "hsss";
    			//$query = "UPDATE `users` SET `lastNotifID` = '{$xxx}' WHERE `keyID`='{$users[$k]['keyID']}'";
                $result = mysql_query($query);
    		}
    	}
    }*/
    //sendmail($email, "New EvE Online notification update", $text);
    endlog($msg);
?>