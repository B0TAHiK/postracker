<?php
set_time_limit(3600);
//Requiring some libs...
require_once dirname(__FILE__) . '/../db_con.php';
require_once dirname(__FILE__) . '/../init.php';
//Connecting to DB...
$msg = "Connecting to DB... ";
$db->openConnection();
if ($db->pingServer() === False) {
    $msg .= "\n[fail]Server is not responding!";
}
$query = "SELECT * FROM `apilist`";
$result = $db->query($query);
//Getting APIs from DB...
$msg .= "\nCollecting API keys... ";
$keyIDarr = array();
$vCodearr = array();
while($row = $db->fetchAssoc($result)){
    if((api::get_mask($row[keyID], $row[vCode]) & 131072) > 0){ // StarbaseDetail
        $keyIDarr[] = $row[keyID];
        $vCodearr[] = $row[vCode];
    }
}
if(count($keyIDarr) > 0) $msg .= " found " . count($keyIDarr) . " API keys"; else logs::endlog($msg . " found none");
//Running script for each API...
for ($k = 0; $k < count($keyIDarr); $k++) {
    //Getting XML...
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    $pageChar = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
    $apiChar = api::api_req($pageChar, $keyID, $vCode);
    $ownerID = strval($apiChar->result->key->rowset->row->attributes()->corporationID);
    $allyownerID = (strval($apiChar->result->key->rowset->row->attributes()->allianceID) != "0") ? strval($apiChar->result->key->rowset->row->attributes()->allianceID) : "1";
    $page = "https://api.eveonline.com/corp/Starbasedetail.xml.aspx";
    $idkind = "itemID";
    //Getting POS list...
    $msg .= "\nGetting POS list for key " . $keyID . "...";
    $query = "SELECT `posID` FROM `poslist` WHERE `ownerID` = '$ownerID'";
    $result = $db->query($query);
    if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result);
    $posID = array();
    while($row = $db->fetchRow($result)){
        $posID[] = $row[0];
    }
    //Running script for each POS...
    foreach ($posID as $posIDQuery):
        //Getting API information...
        $msg .= "\nGetting POS id " . $posIDQuery . " info...";
        $apiPosDetails = api::api_req($page, $keyID, $vCode, $idkind, $posIDQuery, '', '');
        $msg .= " Current Time: " . strval($apiPosDetails->currentTime) . " Cached Until: " . strval($apiPosDetails->cachedUntil);
        $i=0;
        //Parsing XML...
        foreach ($apiPosDetails->result->rowset->row as $row):
            $data[$i] = array (
                'posFuelID' => strval($row[typeID]),
                'posFuelQuantity' => strval($row[quantity])
            );
            //Distinguishing fuel blocs from Strontium Clathrates...
            if ($data[$i][posFuelID] == 16275) {
                $msg .= "\nStrontium clathrates:";
                //Getting CT type...
                $msg .= " CT type... ";
                $query = "SELECT * FROM `poslist` WHERE `posID`='$posIDQuery' LIMIT 1";
                $result = $db->query($query);
                $table = $db->fetchAssoc($result);
                $type = $table[typeID];
                $systemID = $table[locationID];
                if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result);
                //Calculating Estimated time...
                $msg .= " Calculating Estimated time.";
                $data[$i]['stront'] = $data[$i][posFuelQuantity];
                $stront = $data[$i][stront];
                $time = posmonCalculations::calc_stront_time($type, $systemID, $stront, $allyownerID, $msg);
                //Adding information to the DB...
                $msg .= " Adding information to the DB... ";
                $query = "UPDATE `poslist` SET `stront` = '$stront', `rfTime` = '$time' WHERE `posID`='$posIDQuery'";
                $result = $db->query($query);
                if(gettype($result) === object OR $result === TRUE) $msg .= "[ok]"; else logs::endlog($msg . $result);
            } else {
                $msg .= "\nFuel blocs: ";
                //Getting CT type...
                $msg .= " CT type... ";
                $query = "SELECT `typeid` FROM `poslist` WHERE `posID`='$posIDQuery' LIMIT 1";
                $result = $db->query($query);
                $typeRow = $db->fetchRow($result);
                $type = $typeRow[0];
                if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result);
                //Calculating Estimated time...
                $msg .= " Calculating Estimated time.";
                $data[$i]['fuel'] = $data[$i][posFuelQuantity];
                $fuel = $data[$i][fuel];
                $fuelph = posmonCalculations::calc_fuel_time($type, $systemID, $allyownerID, $msg);
                $time = floor($fuel / $fuelph);
                //Adding information to the DB...
                $msg .= " Adding information to the DB... ";
                $query = "UPDATE `poslist` SET `fuel` = '$fuel', `fuelph` = '$fuelph', `time` = '$time' WHERE `posID`='$posIDQuery'";
                $result = $db->query($query);
                if(gettype($result) === object OR $result === TRUE) $msg .= "[ok]"; else logs::endlog($msg . $result);
            }
            $i++;
        endforeach;
        //Checking if POS is in Reinfoce Mode...
        $msg .= "\nChecking if POS is in Reinfoce Mode... ";
        $query = "SELECT `posID` FROM `poslist` WHERE `posID` = '$posIDQuery' AND `state` = '3'";
        $result = $db->query($query);
        if(gettype($result) === object) $msg .= "[ok]"; else logs::endlog($msg . $result);
        $num = $db->countRows($result);
        if ($num === 1) {
            //If yes, setting amount of Strontium to 0...
            $msg .= " POS in RF, setting amount of Strontium to 0... ";
            $query = "UPDATE `poslist` SET `stront` = '0' WHERE `posID`='$posIDQuery'";
            $result = $db->query($query);
            if(gettype($result) === object OR $result === TRUE) $msg .= "[ok]"; else logs::endlog($msg . $result);
        }
    endforeach;
}
$db->closeConnection();
logs::endlog($msg);

?>
