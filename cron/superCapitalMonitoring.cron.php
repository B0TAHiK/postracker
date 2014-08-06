<?php

//Requiring some libs...
require_once dirname(__FILE__) . '/../db_con.php';
require_once dirname(__FILE__) . '/../init.php';
//Connecting to the DB...
$db->openConnection();

//Getting supercapital type IDs...
$query = "SELECT `typeID` FROM `invTypes` WHERE `groupID` IN ('659', '30')";
$result = mysql_query($query) OR die (mysql_error());
$superCapitalTypeIDs = array();
while ($superCapitalID = $db->fetchRow($result)) {
    $superCapitalTypeIDs[] = $superCapitalID[0];
}
//Acquiring APIs...
$query = "SELECT * FROM `apilist`";
$result = $db->query($query);
$keyIDarr = array();
$vCodearr = array();
while($row = $db->fetchAssoc($result)){
    if((api::get_mask($row[keyID], $row[vCode]) & 33554432) > 0){
        $keyIDarr[] = $row[keyID];
        $vCodearr[] = $row[vCode];
    }
}
for ($k = 0; $k < count($keyIDarr); $k++) {
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    /*$mask = api::get_mask($keyID, $vCode);
    $maskNeeded = 33554432;
    if (($mask & $maskNeeded) <= 0) {
        //No Access
        continue;
    }*/
//    Making API request...
    $page = "https://api.eveonline.com/corp/MemberTracking.xml.aspx";
    $kindid = "extended";
    $id = "1";
    $api = api::api_req($page, $keyID, $vCode, $kindid, $id);
    //Getting corp information...
    $pageCorp = "https://api.eveonline.com/corp/CorporationSheet.xml.aspx";
    $apiCorp = api::api_req($pageCorp, $keyID, $vCode)->result;
    $apiSheet = xml2array($apiCorp);
    $corporationID = strval($apiSheet[corporationID]);
    $corporationName = strval($apiSheet[corporationName]);
    //Removing obsolete chars from the DB...
    $query = "SELECT * FROM `superCapitalList` WHERE `corporationID` = '$corporationID'";
    $result = $db->query($query);
    while ($corpSupers = $db->fetchAssoc($result)) {
        $ifInCorp = $api->xpath("/eveapi/result/rowset/row[@characterID=$corpSupers[characterID]]");
        if ($ifInCorp == NULL) {
            $query = "DELETE FROM `superCapitalList` WHERE `characterID` = '$corpSupers[characterID]'";
            $result2 = $db->query($query);
        }
    }
    //Making list of chars...
    foreach ($api->result->rowset->row as $row) {
        //Checking if supercapital pilot in DB is not sitting in supercap...
        $query = "SELECT `shipTypeID` FROM `superCapitalList` WHERE = `characterID` = '$row[characterID]'";
        $result = $db->query($query);
        if (!in_array($row[shipTypeID], $superCapitalTypeIDs)) {
            //If yes, deleting...
            $query = "DELETE FROM `superCapitalList` WHERE = `characterID` = '$row[characterID]'";
        }
        //Checking if char is in supercap...
        if(in_array($row[shipTypeID], $superCapitalTypeIDs)) {
            
            $query = "SELECT `regionID` FROM `mapDenormalize` WHERE `solarSystemID` = '$row[locationID]'";
            $result = $db->query($query);
            $regionID = mysql_result($result, 0);
            $query = "SELECT `itemName` FROM `invNames` WHERE `itemID` = '$regionID'";
            $result = $db->query($query);
            $region = mysql_result($result, 0);
            
            $query = "SELECT `security` FROM `mapSolarSystems` WHERE `solarSystemID` = '$row[locationID]'";
            $result = $db->query($query);
            if (mysql_result($result, 0) < 0) {
                $SS = 0.0;
            } else {
                $SS = round(mysql_result($result, 0), 1);
            }
            
            if ($row[shipType] == "Avatar" OR $row[shipType] == "Erebus" OR $row[shipType] == "Leviathan" OR $row[shipType] == "Ragnarok") {
                $shipClass = "Titan";
            } else {
                $shipClass = "Mothership";
            }
            $query = "SELECT * FROM `superCapitalList` WHERE `characterID` = '$row[characterID]' LIMIT 1";
            $result = $db->query($query);
            //If yes, checking if char in DB...
            if ($db->countRows($result) == 1) {
                //If yes, updating...
                $query = "UPDATE `superCapitalList` SET `characterName` = '$row[name]', `corporationID` = '$corporationID', `corporationName` = '$corporationName', `logonDateTime` = '$row[logonDateTime]', `logoffDateTime` = '$row[logoffDateTime]', `locationID` = '$row[locationID]', `SS` = '$SS', `locationName` = '$row[location]', `regionName` = '$region', `shipTypeID` = '$row[shipTypeID]', `shipTypeName` = '$row[shipType]', `shipClass` = '$shipClass' WHERE `characterID`='$row[characterID]'";
                $result = $db->query($query);
            } else {
                //If no, adding...
                $query = "INSERT INTO `superCapitalList` SET `characterID` = '$row[characterID]', `characterName` = '$row[name]', `corporationID` = '$corporationID', `corporationName` = '$corporationName', `logonDateTime` = '$row[logonDateTime]', `logoffDateTime` = '$row[logoffDateTime]', `locationID` = '$row[locationID]', `SS` = '$SS', `locationName` = '$row[location]', `regionName` = '$region', `shipTypeID` = '$row[shipTypeID]', `shipTypeName` = '$row[shipType]', `shipClass` = '$shipClass'";
                $result = $db->query($query);
            }
        }
    }
}

?>
