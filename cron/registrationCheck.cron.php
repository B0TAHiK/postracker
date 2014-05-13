<?php
define("PATH", "/var/www/pos/");
require_once PATH . 'db_con.php';
require_once PATH . 'functions.php';
mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
mysql_select_db($db_name) or die(mysql_error());
$query = "SELECT * FROM `users`";
$result = mysql_query($query);
$keyIDarr = array();
$vCodearr = array();
$chararr = array();
while($row = mysql_fetch_assoc($result)){
        $keyIDarr[] = $row[keyID];
        $vCodearr[] = $row[vCode];
        $chararr[] = $row[char];
}
for ($k = 0; $k < count($keyIDarr); $k++) {
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    $char = $chararr[$k];
    $page = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
    $api = api_req($page, $keyID, $vCode, '', '', '', '');
    $characterID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@characterID");
    $characterID = strval($characterID[0][characterID]);
    $corporationID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@corporationID");
    $corporationID = strval($corporationID[0][corporationID]);
    $allianceID = $api->xpath("/eveapi/result/key/rowset/row[@characterName='$char']/@allianceID");
    $allianceID = strval($allianceID[0][allianceID]);
    $query = "SELECT * FROM `allowedUsers` WHERE `characterID` = '$characterID' OR `corporationID`= '$corporationID' OR `allianceID` = '$allianceID' LIMIT 1";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 1) {
        //Дописать разбанивалку
//        $groupID = 1;
        $query = "UPDATE `users` SET `characterID` = '$characterID', `corporationID`= '$corporationID', `allianceID` = '$allianceID' WHERE `keyID`='$keyID'";
        $result = mysql_query($query);
    } else {
        $groupID = 0;
        $query = "UPDATE `users` SET `groupID` = '$groupID', `characterID` = '$characterID', `corporationID`= '$corporationID', `allianceID` = '$allianceID' WHERE `keyID`='$keyID'";
        $result = mysql_query($query);
    }
}