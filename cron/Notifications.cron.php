<?php

//Requiring some libs...
require_once dirname(__FILE__) . '/../db_con.php';
require_once dirname(__FILE__) . '/../init.php';
//Connecting to DB...
$msg = "Connecting to DB... ";
$db->openConnection();
$msg .= ($db->pingServer() === False) ? "[fail] Server is not responding!" : "[ok]";
$query = "SELECT * FROM `users`";
$result = $db->query($query);
//Getting APIs from DB...
$msg .= "\nCollecting API keys... ";
if(gettype($result) != object) logs::endlog($msg . $result);
$users = array();
$deld = array();
while($row = $db->fetchAssoc($result)){
    if((api::get_mask($row[keyID], $row[vCode]) & 49152) > 1){ // Notifications & NotificationTexts
        $users[] = array(
		    'keyID' => $row[keyID],
    	    'vCode' => $row[vCode],
    	    'characterID' => $row[characterID],
    	    'corporationID' => $row[corporationID],
    	    'allianceID' => $row[allianceID],
    	    'lastNotifID' => $row[lastNotifID],
            'mailNotif' => $row[mailNotif],
    	    'email' => $row[email],
            'JID' => $row[JID],
            'groupID' => $row[groupID]
        );
    }
}
if(count($users) > 0) $msg .= " found " . count($users) . " API keys"; else logs::endlog($msg . " found none");
$i=0;
for ($k = 0; $k < count($users); $k++) {
    //Getting XML...
    $page = "https://api.eveonline.com/char/Notifications.xml.aspx";
    $api = api::api_req($page, $users[$k][keyID], $users[$k][vCode], 'characterID', $users[$k][characterID], '', '');
    $msg .=  "\nCurrent Time: " . strval($api->currentTime) . " Cached Until: " . strval($api->cachedUntil);
    //Parsing XML...
    $msg .= "\nParsing Notifications for key " . $users[$k][keyID];
    foreach ($api->result->rowset->row as $row):
    	$rpt = FALSE;
    	$rtype = FALSE;
    	$notificationID = strval($row[notificationID]);
    	$msg .= "\nFound Notifications id " . $notificationID . "... ";
    	$typearr = array(37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 75, 76, 77, 78, 79, 80, 86, 87, 88, 93); // http://wiki.eve-id.net/APIv2_Char_Notifications_XML
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
    $result = $db->query($query);
    if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result);
    $num = $db->countRows($result);
    if($num === 0){
    	$msg .= " Inserting new notification... ";
        //echo addslashes(notifications::ParsingNotifText($data[$k]['NotificationText'], $data[$k]['corporationID'], $data[$k]['allianceID']));
        $notixtxttosql = ($data[$k]['typeID']==76) ? addslashes(notifications::ParsingNotifText($data[$k]['NotificationText'], 0, 0)) : addslashes(notifications::ParsingNotifText($data[$k]['NotificationText'], $data[$k]['corporationID'], $data[$k]['allianceID']));
    	$query = "INSERT INTO `notifications` SET `notificationID` = '{$data[$k]['notificationID']}', `typeID` = '{$data[$k]['typeID']}', `senderID` = '{$data[$k]['senderID']}', `senderName` = '{$data[$k]['senderName']}',
    	 `sentDate` = '{$data[$k]['sentDate']}', `NotificationText` = '$notixtxttosql', `corporationID` = '{$data[$k]['corporationID']}', `allianceID` = '{$data[$k]['allianceID']}'";
    	$result = $db->query($query);
    	if(gettype($result) === object OR $result === TRUE) $msg .= "[ok]"; else logs::endlog($msg . $result);
    } else $msg .= " Notification already exists.";
}
$msg .= "\nSending e-mails and jabber message with new notifications";
for ($k = 0; $k < count($users); $k++){
    $msg .= "\nUser e-mail: " . $users[$k][email];
    if($users[$k][mailNotif] > 0 && $users[$k][groupID] > 0){ //0 - none, 1 - email, 2 - jabber, 3 - all
        $mailtext = NULL;
        $query = ($users[$k][groupID] == 1) ? "SELECT `notificationID`, `typeID`, `sentDate`, `NotificationText` FROM `notifications` WHERE `notificationID` > '{$users[$k][lastNotifID]}' AND `corporationID` = '{$users[$k][corporationID]}'" :
         "SELECT `notificationID`, `typeID`, `sentDate`, `NotificationText` FROM `notifications` WHERE `notificationID` > '{$users[$k][lastNotifID]}'";
        $result = $db->query($query);
        $notifArr = $db->toArray($result);
        if(gettype($result) != object) logs::endlog($msg . "\n" . $result);
        for ($j = 0; $j < $db->countRows($result); $j++){
            if($notifArr[$j][typeID]==76){
                $tmparr = yaml_parse($notifArr[$j][NotificationText]);
                for($h=0; $h < count($tmparr[wants]); $h++){
                    if($tmparr[wants][$h][typeID] = 4246 || $tmparr[wants][$h][typeID] = 4247 || $tmparr[wants][$h][typeID] = 4051 || $tmparr[wants][$h][typeID] = 4312){ // Fuel Block ids
                        $query2 = "SELECT `fuelph` FROM `poslist` WHERE `typeID` = '{$tmparr[typeID]}' LIMIT 1";
                        $result2 =  $db->query($query2);
                        $fuelph = $db->fetchRow($result2)[0];
                        if(gettype($result2) != object) logs::endlog($msg . "\n" . $result2);
                        if($tmparr[wants][$h][quantity] >= $fuelph*23 && $tmparr[wants][$h][quantity] < $fuelph*24)
                            $mailtext .= notifications::GenerateMailText($notifArr[$j][typeID], $notifArr[$j][sentDate], $notifArr[$j][NotificationText]);
                        if($tmparr[wants][$h][quantity] >= $fuelph*3 && $tmparr[wants][$h][quantity] < $fuelph*4)
                            $mailtext .= notifications::GenerateMailText($notifArr[$j][typeID], $notifArr[$j][sentDate], $notifArr[$j][NotificationText]);
                    }
                }
            } else $mailtext .= notifications::GenerateMailText($notifArr[$j][typeID], $notifArr[$j][sentDate], $notifArr[$j][NotificationText]);
        }
        if($mailtext != NULL){
            if($users[$k][mailNotif] & 1) $msg .= (notifications::sendmail($users[$k][email], "New EvE Online notification update", date(DATE_RFC822) . " New notifications arrived.\n" . $mailtext)) ? " [mail ok]" : " [mail fail]";
            if($users[$k][mailNotif] & 2) $msg .= notifications::sendjabber($users[$k][JID], $mailtext);
            $lastnotif = 0;
            for($j = 0; $j < count($data); $j++) if($data[$j]['notificationID'] > $users[$k]['lastNotifID'] && $lastnotif < $data[$j]['notificationID']) $lastnotif = $data[$j]['notificationID'];
            if($lastnotif > 0){
                $query = "UPDATE `users` SET `lastNotifID` = '{$lastnotif}' WHERE `keyID`='{$users[$k]['keyID']}'";
                $result = $db->query($query);
                if(gettype($result) === object OR $result === TRUE) $msg .= " [last notif id updated]"; else logs::endlog($msg . "\n" . $result);
            }
        } else $msg .= " no new notifications";
    } else  $msg .= " doesn't wish or have permission to receive e-mails";
}
logs::endlog($msg);

?>
