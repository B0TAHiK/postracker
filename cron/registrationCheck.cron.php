<?php

require_once dirname(__FILE__) . '/../db_con.php';
require_once dirname(__FILE__) . '/../init.php';
$msg = date(DATE_RFC822) . "\nConnecting to DB... ";
$db->openConnection();
if(!mysql_error()) {
    
    if(!mysql_error()) $msg .= "[ok]"; else logs::endlog($msg . mysql_error());
} else logs::endlog($msg . mysql_error());
$query = "SELECT * FROM `users`";
$msg .= "\nCollecting users API keys... ";
$result = $db->query($query);
$keyIDarr = array();
$vCodearr = array();
$chararr = array();
while($row = $db->fetchAssoc($result)){
    $keyIDarr[] = $row[keyID];
    $vCodearr[] = $row[vCode];
    $chararr[] = $row[char];
}
if(count($keyIDarr) > 0) $msg .= " found " . count($keyIDarr) . " API keys"; else logs::endlog($msg . " found none");
for ($k = 0; $k < count($keyIDarr); $k++) {
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    $char = $chararr[$k];
    $msg .= "\nParsing info for key " . $keyID . " (" . $char . ")";
    $page = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
    $api = api::api_req($page, $keyID, $vCode, '', '', '', '');
    $characterID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@characterID");
    $characterID = strval($characterID[0][characterID]);
    $corporationID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@corporationID");
    $corporationID = strval($corporationID[0][corporationID]);
    $allianceID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@allianceID");
    $allianceID = strval($allianceID[0][allianceID]);
    $query = "SELECT `groupID` FROM `users` WHERE `keyID`='$keyID' LIMIT 1";
    $result = $db->query($query);
    $groupID = mysql_result($result, 0);
    $msg .= ", characterID=" . $characterID . ", corporationID=" . $corporationID . ", allianceID=" . $allianceID . ", groupID=" . $groupID;
    $query = "SELECT * FROM `allowedUsers` WHERE `characterID` = '$characterID' OR `corporationID`= '$corporationID' OR `allianceID` = '$allianceID' LIMIT 1";
    $result = $db->query($query);
    if ($db->countRows($result) == 1) {
        $maskAPI = api::get_mask($keyID, $vCode);
        if (($maskAPI & 49152) <= 0) {
            if($groupID > 0) $groupID *= -1;
            $query = "UPDATE `users` SET `groupID` = '$groupID', `characterID` = '$characterID', `corporationID`= '$corporationID', `allianceID` = '$allianceID' WHERE `keyID`='$keyID'";
            $result = $db->query($query);
            $msg .= " [wrong mask ". $maskAPI . "]";
        } else {
            if($groupID < 0) $groupID *= -1;
            $query = "UPDATE `users` SET `groupID` = '$groupID', `characterID` = '$characterID', `corporationID`= '$corporationID', `allianceID` = '$allianceID' WHERE `keyID`='$keyID'";
            $result = $db->query($query);
            $msg .= " [ok]";
        }
    } else {
        if($groupID > 0) $groupID *= -1;
        $query = "UPDATE `users` SET `groupID` = '$groupID', `characterID` = '$characterID', `corporationID`= '$corporationID', `allianceID` = '$allianceID' WHERE `keyID`='$keyID'";
        $result = $db->query($query);
        $msg .= " [not allowed]";
    }
}
logs::endlog($msg);

?>
