<?php
//Requiring some libs...
require_once 'db_con.php';
require_once 'functions.php';
//Connecting to the DB...
mysql_connect($hostname, $username, $mysql_pass);
mysql_select_db($db_name);
//Getting supercapital type IDs...
$query = "SELECT `typeID` FROM `invTypes` WHERE `groupID` IN ('659', '30')";
$result = mysql_query($query) OR die (mysql_error());
$superCapitalTypeIDs = array();
while ($superCapitalID = mysql_fetch_row($result)) {
    $superCapitalTypeIDs[] = $superCapitalID[0];
}
//Acquiring APIs...
$query = "SELECT * FROM `apilist`";
$result = mysql_query($query);
$keyIDarr = array();
$vCodearr = array();
while($row = mysql_fetch_assoc($result)){
    $keyIDarr[] = $row[keyID];
    $vCodearr[] = $row[vCode];
}
for ($k = 0; $k < count($keyIDarr); $k++) {
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    $mask = get_mask($keyID, $vCode);
    $maskNeeded = 33554432;
    if (($mask & $maskNeeded) <= 0) {
        //No Access
        continue;
    }
//    Making API request...
    $page = "https://api.eveonline.com/corp/MemberTracking.xml.aspx";
    $kindid = "extended";
    $id = "1";
    $api = api_req($page, $keyID, $vCode, $kindid, $id, '', '');
    //Getting corp information...
    $pageCorp = "https://api.eveonline.com/corp/CorporationSheet.xml.aspx";
    $apiCorp = api_req($pageCorp, $keyID, $vCode, '', '', '', '')->result;
    $apiSheet = xml2array($apiCorp);
    $corporationID = strval($apiSheet[corporationID]);
    $corporationName = strval($apiSheet[corporationName]);
    //Removing obsolete chars from the DB...
    $query = "SELECT `corporationID` FROM `superCapitalList`";
    $result = mysql_query($query);
    while ($corporationIDAPI = mysql_fetch_row($result)) {
        if ($corporationIDAPI[0] != $corporationID) {
            $query = "DELETE FROM `superCapitalList` WHERE `corporationID` = '$corporationIDAPI[0]'";
            $result2 = mysql_query($query) or die(mysql_error());
        }
    }
    //Making list of chars...
    foreach ($api->result->rowset->row as $row) {
        //Checking if supercapital pilot in DB is not sitting in supercap...
        $query = "SELECT `shipTypeID` FROM `superCapitalList` WHERE = `characterID` = '$row[characterID]'";
        $result = mysql_query($query);
        if (!in_array($row[shipTypeID], $superCapitalTypeIDs)) {
            //If yes, deleting...
            $query = "DELETE FROM `superCapitalList` WHERE = `characterID` = '$row[characterID]'";
        }
        //Checking if char is in supercap...
        if(in_array($row[shipTypeID], $superCapitalTypeIDs)) {
            
            $query = "SELECT `regionID` FROM `mapDenormalize` WHERE `solarSystemID` = '$row[locationID]'";
            $result = mysql_query($query);
            $regionID = mysql_result($result, 0);
            $query = "SELECT `itemName` FROM `invNames` WHERE `itemID` = '$regionID'";
            $result = mysql_query($query);
            $region = mysql_result($result, 0);
            
            $query = "SELECT `security` FROM `mapSolarSystems` WHERE `solarSystemID` = '$row[locationID]'";
            $result = mysql_query($query);
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
            $result = mysql_query($query);
            //If yes, checking if char in DB...
            if (mysql_num_rows($result) == 1) {
                //If yes, updating...
                $query = "UPDATE `superCapitalList` SET `characterName` = '$row[name]', `corporationID` = '$corporationID', `corporationName` = '$corporationName', `logonDateTime` = '$row[logonDateTime]', `logoffDateTime` = '$row[logoffDateTime]', `locationID` = '$row[locationID]', `SS` = '$SS', `locationName` = '$row[location]', `regionName` = '$region', `shipTypeID` = '$row[shipTypeID]', `shipTypeName` = '$row[shipType]', `shipClass` = '$shipClass' WHERE `characterID`='$row[characterID]'";
                $result = mysql_query($query);
            } else {
                //If no, adding...
                $query = "INSERT INTO `superCapitalList` SET `characterID` = '$row[characterID]', `characterName` = '$row[name]', `corporationID` = '$corporationID', `corporationName` = '$corporationName', `logonDateTime` = '$row[logonDateTime]', `logoffDateTime` = '$row[logoffDateTime]', `locationID` = '$row[locationID]', `SS` = '$SS', `locationName` = '$row[location]', `regionName` = '$region', `shipTypeID` = '$row[shipTypeID]', `shipTypeName` = '$row[shipType]', `shipClass` = '$shipClass'";
                $result = mysql_query($query) OR die(mysql_error());
            }
        }
    }
}