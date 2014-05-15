<?php
//Requiring some libs...
require_once 'db_con.php';
require_once 'functions.php';
//Connecting to the DB...
mysql_connect($hostname, $username, $mysql_pass);
mysql_select_db($db_name);
//Acquiring APIs...
$query = "SELECT * FROM `apilist`";
$result = mysql_query($query);
$keyIDarr = array();
$vCodearr = array();
$maskarr = array();
while($row = mysql_fetch_assoc($result)){
    $keyIDarr[] = $row[keyID];
    $vCodearr[] = $row[vCode];
    $maskarr[] = $row[mask];
}
for ($k = 0; $k < count($keyIDarr); $k++) {
    $keyID = $keyIDarr[$k];
    $vCode = $vCodearr[$k];
    $mask = $maskarr[$k];
//    Making API request...
    $maskAPI = get_mask($keyID, $vCode);
    if ($mask != $maskAPI) {
        $query = "UPDATE `apilist` SET `mask` = '$maskAPI' WHERE `keyID` = '$keyID'";
        $result = mysql_query($query);
    }
}