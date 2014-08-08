<?php

set_time_limit(300);
//Requiring some libs...
require_once dirname(__FILE__) . '/../db_con.php';
require_once dirname(__FILE__) . '/../init.php';
//Connecting to DB...

$msg = "Connecting to DB... ";
$db->openConnection();
$msg .= ($db->pingServer() === False) ? "[fail] Server is not responding!" : "[ok]";
//StarbaseList parsing...
$msg .= "\nCollecting API keys... ";
$page = "https://api.eveonline.com/corp/StarbaseList.xml.aspx";
$query = "SELECT * FROM `apilist`";
$result = $db->query($query);
if(gettype($result) != object) logs::endlog($msg . $result);
//Getting APIs from DB...
$keyIDarr = array();
$vCodearr = array();
while($row = $db->fetchAssoc($result)){
    if((api::get_mask($row[keyID], $row[vCode]) & 524288) > 0){ // StarbaseList
        $keyIDarr[] = $row[keyID];
        $vCodearr[] = $row[vCode];
    }
}
if( count( $keyIDarr ) > 0 ) $msg .= " found " . count( $keyIDarr ) . " API keys"; else logs::endlog($msg . " found none");
//Running script for each API...
for ($k = 0; $k < count($keyIDarr); $k++) {
    unset($data);
    //Getting XML...
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    $api = api::api_req($page, $keyID, $vCode);
    $i=0;
    //Parsing XML...
    $msg .= "\nParsing POS ids for key " . $keyID;
    foreach ( $api->result->rowset->row as $row):
        $msg .= ", " . strval($row[itemID]);
        $data[$i] = array(
            'posID' => strval($row[itemID]),
            'typeID' => strval($row[typeID]),
            'locationID' => strval($row[locationID]),
            'moonID' => strval($row[moonID]),
            'state' => strval($row[state]),
            'stateTimestamp' => strval($row[stateTimestamp])
        );
        $i++;
    endforeach;
    $msg .=  "\nCurrent Time: " . strval($api->currentTime) . " Cached Until: " . strval($api->cachedUntil);
    //Getting names from CCP MySQL DB...
    for ($i = 0; $i < count($data); $i++) {
        $moonID = $data[$i][moonID];
        $typeID = $data[$i][typeID];
        //Getting moon coordinates...
        $msg .= "\nMoon id: " . $moonID . ". Getting coordinates... ";
        $query = "SELECT `itemName` FROM  `mapDenormalize` WHERE `itemID`='$moonID' LIMIT 1";
        $result = $db->query($query);
        if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result); 
            $row = $db->fetchAssoc($result);
            $data[$i]['moonName'] =$row[itemName];
        //Getting CT type...
            $msg .= " Getting CT type... ";
        $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='$typeID' LIMIT 1";
        $result = $db->query($query);
        $row = $db->fetchAssoc($result);
        if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result);
        $data[$i]['typeName'] = $row[typeName];
    }
    //Adding information to the DB...
    for ($i = 0; $i < count($data); $i++) {
        $moonID = $data[$i][moonID];
        $typeID = $data[$i][typeID];
        $posID = $data[$i][posID];
        $locationID = $data[$i][locationID];
        $state = $data[$i][state];
        $stateTimestamp = $data[$i][stateTimestamp];
        $moonName = $data[$i][moonName];
        $typeName = $data[$i][typeName];
        //Checking for obsolete records...
        $msg .= "\nPOS id " . $posID . ". Checking for obsolete records... ";
        $query = "SELECT `posID` FROM `poslist`";
        $result = $db->query($query);
        if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result);               
            $pageChar = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
            $apiChar = api::api_req($pageChar, $keyID, $vCode, '', '', '', '');
            $ownerID = strval($apiChar->result->key->rowset->row->attributes()->corporationID);
        $ownerName = strval($apiChar->result->key->rowset->row->attributes()->corporationName);
        while($poslist = $db->fetchArray($result)){
            for($j=0;$j<=count($data);$j++){
                if($data[$j]['posID']==$poslist[0]) break;
                if($j==count($data)){
                     //Deleting obsolete records, if found...
                    $db->query("DELETE FROM `poslist` WHERE `posID`='{$poslist[0]}' AND `ownerID` = '$ownerID'");	
                    if(!mysql_error()){
                        if($db->affectedRows($db->lastQuery())!=0) $msg .= " Deleting obsolete records... [ok]";
                    } else logs::endlog($msg . " Deleting obsolete records... " . $result());		
                }
            }
        }
        // Looking for old records...
        $msg .= " Looking for old records... ";
        $query = "SELECT `posID` FROM `poslist` WHERE `posID`='$posID' LIMIT 1";
        $result = $db->query($query);
        if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result);
        $num = $db->countRows($result);
        //If found, updating...
        if ($num === 1) {
            $msg .= " Found old record, updating... ";
            $query = "UPDATE `poslist` SET  `locationID` = '$locationID', `moonID` = '$moonID', `state` = '$state', `stateTimestamp` = '$stateTimestamp', `moonName` = '$moonName', `typeName` = '$typeName', `ownerID` = '$ownerID', `ownerName`= '$ownerName' WHERE `posID`='$posID'";
            $result = $db->query($query);
            if(gettype($result) === object OR $result === TRUE) $msg .= "[ok]"; else logs::endlog($msg . $result);
        } else {
        //If not, creating new row...
            $msg .= " Not found old record, creating new... ";
            $query = "INSERT INTO `poslist` SET `posID`= '$posID', `typeID` = '$typeID', `locationID` = '$locationID', `moonID` = '$moonID', `state` = '$state', `stateTimestamp` = '$stateTimestamp', `moonName` = '$moonName', `typeName` = '$typeName', `ownerID` = '$ownerID', `ownerName`= '$ownerName'";
            $result = $db->query($query);
            if(gettype($result) === object OR $result === TRUE) $msg .= "[ok]"; else logs::endlog($msg . $result);
        };
    }
    //StarbaseList parsing finished
}
$db->closeConnection();
logs::endlog($msg);

?>
