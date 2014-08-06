<?php
//Requiring some libs...
require_once dirname(__FILE__) . '/../db_con.php';
require_once dirname(__FILE__) . '/../init.php';
//Connecting to the DB...
$db->openConnection();

//Acquiring APIs...
$query = "SELECT * FROM `apilist`";
$result = $db->query($query);
$keyIDarr = array();
$vCodearr = array();
$maskarr = array();
$corparr = array();
while($row = $db->fetchAssoc($result)){
    $keyIDarr[] = $row[keyID];
    $vCodearr[] = $row[vCode];
    $maskarr[] = $row[mask];
    $corparr[] = $row[corporation];
}
for ($k = 0; $k < count($keyIDarr); $k++) {
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    $mask = $maskarr[$k];
    $corp = $corparr[$k];
//    Making API request...
    $maskAPI = api::get_mask($keyID, $vCode);
    $page = "https://api.eveonline.com/account/apiKeyInfo.xml.aspx";
    $api = api::api_req($page, $keyID, $vCode, '', '', '', '');
    //$corporationName = $api->result->key->rowset->row->attributes()->corporationName;
    $corporationName = $api->xpath("/eveapi/result/key/rowset/row/@corporationName");
    if ($mask != $maskAPI) {
        $query = "UPDATE `apilist` SET `mask` = '$maskAPI' WHERE `keyID` = '$keyID'";
        $result = $db->query($query);
    }
    if ($corp != $corporationName) {
        $query = "UPDATE `apilist` SET `corporation` = '{$corporationName[0][0]}' WHERE `keyID` = '$keyID'";
        $result = $db->query($query);
    }
}