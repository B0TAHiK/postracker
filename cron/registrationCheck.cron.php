<?php

require_once dirname(__FILE__) . '/../db_con.php';
require_once dirname(__FILE__) . '/../functions.php';
$msg = date(DATE_RFC822) . "\nConnecting to DB... ";
mysql_connect($hostname, $username, $mysql_pass);
if(!mysql_error()) {
    mysql_select_db($db_name);
    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
} else endlog($msg . mysql_error());
$query = "SELECT * FROM `users`";
$msg .= "\nCollecting users API keys... ";
$result = mysql_query($query);
$keyIDarr = array();
$vCodearr = array();
$chararr = array();
while($row = mysql_fetch_assoc($result)){
    $keyIDarr[] = $row[keyID];
    $vCodearr[] = $row[vCode];
    $chararr[] = $row[char];
}
if(count($keyIDarr) > 0) $msg .= " found " . count($keyIDarr) . " API keys"; else endlog($msg . " found none");
for ($k = 0; $k < count($keyIDarr); $k++) {
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    $char = $chararr[$k];
    $msg .= "\nParsing info for key " . $keyID . " (" . $char . ")";
    $page = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
    $api = api_req($page, $keyID, $vCode, '', '', '', '');
    $characterID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@characterID");
    $characterID = strval($characterID[0][characterID]);
    $corporationID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@corporationID");
    $corporationID = strval($corporationID[0][corporationID]);
    $allianceID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@allianceID");
    $allianceID = strval($allianceID[0][allianceID]);
    $query = "SELECT `groupID` FROM `users` WHERE `keyID`='$keyID' LIMIT 1";
    $result = mysql_query($query);
    $groupID = mysql_result($result, 0);
    $msg .= ", characterID=" . $characterID . ", corporationID=" . $corporationID . ", allianceID=" . $allianceID . ", groupID=" . $groupID;
    $query = "SELECT * FROM `allowedUsers` WHERE `characterID` = '$characterID' OR `corporationID`= '$corporationID' OR `allianceID` = '$allianceID' LIMIT 1";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 1) {
        $maskAPI = get_mask($keyID, $vCode);
        if (($maskAPI & 49152) <= 0) {
            if($groupID > 0) $groupID *= -1;
            $query = "UPDATE `users` SET `groupID` = '$groupID', `characterID` = '$characterID', `corporationID`= '$corporationID', `allianceID` = '$allianceID' WHERE `keyID`='$keyID'";
            $result = mysql_query($query);
            $msg .= " [wrong mask ". $maskAPI . "]";
        } else {
            if($groupID < 0) $groupID *= -1;
            $query = "UPDATE `users` SET `groupID` = '$groupID', `characterID` = '$characterID', `corporationID`= '$corporationID', `allianceID` = '$allianceID' WHERE `keyID`='$keyID'";
            $result = mysql_query($query);
            $msg .= " [ok]";
        }
    } else {
        if($groupID > 0) $groupID *= -1;
        $query = "UPDATE `users` SET `groupID` = '$groupID', `characterID` = '$characterID', `corporationID`= '$corporationID', `allianceID` = '$allianceID' WHERE `keyID`='$keyID'";
        $result = mysql_query($query);
        $msg .= " [not allowed]";
    }
}
endlog($msg);

?>
