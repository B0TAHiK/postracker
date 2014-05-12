<?php
        //Requiring some libs...
        require_once '../functions.php';
        require '../db_con.php';
        //Connecting to DB...
        $msg = "Connecting to DB... ";
        mysql_connect($hostname, $username, $mysql_pass);
        if(!mysql_error()) {
            mysql_select_db($db_name);
            if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
        } else endlog($msg . mysql_error());
        $query = "SELECT * FROM `apilist`";
        $result = mysql_query($query);
        //Getting APIs from DB...
        $msg .= "\nCollecting API keys... ";
        $keyIDarr = array();
        $vCodearr = array();
        while($row = mysql_fetch_assoc($result)){
                $keyIDarr[] = $row[keyID];
                $vCodearr[] = $row[vCode];                
        }
        if(count($keyIDarr) > 0) $msg .= " found " . count($keyIDarr) . " API keys"; else endlog($msg . " found none");
        //Running script for each API...
        for ($k = 0; $k < count($keyIDarr); $k++) {
            unset($data);
            //Getting XML...
            $keyID = $keyIDarr[$k];
            $vCode = $vCodearr[$k];
            $pageChar = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
            $apiChar = api_req($pageChar, $keyID, $vCode, '', '', '', '');
            $ownerID = strval($apiChar->result->key->rowset->row->attributes()->corporationID);
            $page = "https://api.eveonline.com/corp/AssetList.xml.aspx";
            $api = api_req($page, $keyID, $vCode, '', '', '', '');
            $i=0;
            //Parsing XML...
            $msg .= "\nParsing silo ids for key " . $keyID;
            foreach ( $api->result->rowset->row as $row):
                if(strval($row[typeID]) == 14343 && strval($row[flag]) == 0){ //silo
                    $msg .= ", " . strval($row[itemID]);
                    $data[$i] = array(
                        'siloID' => strval($row[itemID]),
                        'itemID' => strval($row->rowset->row[itemID]),
                        'typeID' => strval($row->rowset->row[typeID]),
                        'locationID' => strval($row[locationID]),
                        'quantity' => strval($row->rowset->row[quantity])
                    );
                    $i++;
                }
            endforeach;
            $msg .=  "\nCurrent Time: " . strval($api->currentTime) . " Cached Until: " . strval($api->cachedUntil);
            for ($i = 0; $i < count($data); $i++) {
                //Checking for obsolete records...
                $msg .= "\nSilo id " . $data[$i]['siloID'] . ". Checking for obsolete records... ";
                $query = "SELECT `siloID` FROM `silolist`";
                $result = mysql_query($query);
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());               
                $pageChar = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
                $apiChar = api_req($pageChar, $keyID, $vCode, '', '', '', '');
                $ownerID = strval($apiChar->result->key->rowset->row->attributes()->corporationID);
                while($silolist = mysql_fetch_array($result , MYSQL_NUM)){
                    for($j=0;$j<=count($data);$j++){
                        if($data[$j]['siloID']==$silolist[0]) break;
                        if($j==count($data)){
                            //Deleting obsolete records, if found...
                            mysql_query("DELETE FROM `silolist` WHERE `siloID`='{$silolist[0]}' AND `ownerID` = '$ownerID'");  
                            if(!mysql_error()){
                                if(mysql_affected_rows()!=0) $msg .= " Deleting obsolete records... [ok]";
                            } else endlog($msg . " Deleting obsolete records... " . mysql_error());     
                        }
                    }
                }
                // Looking for old records...
                $msg .= "\nLooking for old records... ";
                $query = "SELECT `siloID` FROM `silolist` WHERE `siloID`='{$data[$i]['siloID']}' LIMIT 1";
                $result = mysql_query($query);
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                $num = mysql_num_rows($result);
                //If found, updating...
                if ($num === 1) {
                    $msg .= " Looking for moon mineral changed... ";
                    $query = "SELECT `typeID` FROM `silolist` WHERE `typeID`='{$data[$i]['typeID']}' LIMIT 1";
                    $result = mysql_query($query);
                    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                    $num2 = mysql_num_rows($result);
                    if ($num2 == 1){
                        $msg .= " Moon mineral didn't change, updating... ";
                        $query = "UPDATE `silolist` SET `quantity` = '{$data[$i]['quantity']}' WHERE `itemID`='{$data[$i]['itemID']}'";
                        $result = mysql_query($query);
                    }
                    else
                    {
                        $msg .= " Moon mineral hanged, updating... ";
                        $msg .= " Getting Moon Mineral type... ";
                        $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='{$data[$i]['typeID']}' LIMIT 1";
                        $result = mysql_query($query);
                        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error());
                        $typeName = mysql_result($result, 0);
                        $msg .= " Getting Moon Mineral volume... ";
                        $query = "SELECT `volume` FROM  `invTypes` WHERE `typeID`='{$data[$i]['typeID']}' LIMIT 1";
                        $result = mysql_query($query);
                        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error());
                        $mmvolume = mysql_result($result, 0);
                        $query = "INSERT INTO `silolist` SET `itemID` = '{$data[$i]['itemID']}', `typeID` = '{$data[$i]['typeID']}', `quantity` = '{$data[$i]['quantity']}', `mmname` = '$typeName', `mmvolume` = '$mmvolume'";
                        $result = mysql_query($query);
                        if(!mysql_error()) $msg .= " Successful created"; else endlog($msg . mysql_error());
                    }
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                } else {
                //If not, creating new row...
                    $msg .= " Not found old record, creating new... ";
                    $msg .= " Getting Moon Mineral type... ";
                    $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='{$data[$i]['typeID']}' LIMIT 1";
                    $result = mysql_query($query);
                    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error());
                    $typeName = mysql_result($result, 0);
                    $msg .= " Getting Moon Mineral volume... ";
                    $query = "SELECT `volume` FROM  `invTypes` WHERE `typeID`='{$data[$i]['typeID']}' LIMIT 1";
                    $result = mysql_query($query);
                    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error());
                    $mmvolume = mysql_result($result, 0);
                    $msg .= " Associate with pos... ";
                    $query = "SELECT `posID` FROM  `poslist` WHERE `locationID`='{$data[$i]['locationID']}' LIMIT 1";
                    $result = mysql_query($query);
                    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error());
                    $posID = mysql_result($result, 0);
                    $query = "INSERT INTO `silolist` SET `locationID`= '{$data[$i]['locationID']}', `itemID` = '{$data[$i]['itemID']}', `siloID` = '{$data[$i]['siloID']}', `typeID` = '{$data[$i]['typeID']}', `quantity` = '{$data[$i]['quantity']}', `mmname` = '$typeName', `mmvolume` = '$mmvolume', `posID` = '$posID', `ownerID` = '$ownerID'";
                    $result = mysql_query($query);
                    if(!mysql_error()) $msg .= " Successful created"; else endlog($msg . mysql_error());
                };
            }            
        }
        endlog($msg);
?>
