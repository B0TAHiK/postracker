<?php
        //Requiring some libs...
        require_once 'functions.php';
        $msg = date(DATE_RFC822) . "<br/>[" . str_repeat("=",100) . "]";
        //Requiring some libs...
        require 'db_con.php';
        //Connecting to DB...
        $msg .= "<br/>Connecting to DB... ";
        mysql_connect($hostname, $username, $mysql_pass);
        if(!mysql_error()) {
            mysql_select_db($db_name);
            if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
        } else endlog($msg . mysql_error());
        $query = "SELECT * FROM `apilist`";
        $result = mysql_query($query);
        //Getting APIs from DB...
        $msg .= "<br/>Collecting API keys... ";
        $keyIDarr = array();
        $vCodearr = array();
        while($row = mysql_fetch_assoc($result)){
                $keyIDarr[] = $row[keyID];
                $vCodearr[] = $row[vCode];                
        }
        if(count($keyIDarr) > 0) $msg .= " found " . count($keyIDarr) . " API keys"; else endlog($msg . " found none");
        //Running script for each API...
        for ($k = 0; $k < count($keyIDarr); $k++) {
            //Getting XML...
            $keyID = $keyIDarr[$k];
            $vCode = $vCodearr[$k];
            $pageChar = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
            $apiChar = api_req($pageChar, $keyID, $vCode, '', '');
            $ownerID = strval($apiChar->result->key->rowset->row->attributes()->corporationID);
            $page = "https://api.eveonline.com/corp/AssetList.xml.aspx";
            $api = api_req($page, $keyID, $vCode, '', '');
            foreach ( $api->result->rowset->row as $row):
                if(strval($row[typeID]) == 14343 && strval($row[flag]) == 0){ //silo
                    $itemID = strval($row->rowset->row[itemID]);
                    $typeID = strval($row->rowset->row[typeID]);
                    $locationID = strval($row[locationID]);
                    $quantity = strval($row->rowset->row[quantity]);
                    // Looking for old records...
                    $msg .= "<br/>Looking for old records... ";
                    $query = "SELECT `itemID` FROM `silolist` WHERE `itemID`='$itemID' LIMIT 1";
                    $result = mysql_query($query);
                    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                    $num = mysql_num_rows($result);
                    //If found, updating...
                    if ($num === 1) {
                        $msg .= " Found old record, updating... ";
                        $query = "UPDATE `silolist` SET `quantity` = '$quantity' WHERE `itemID`='$itemID'";
                        $result = mysql_query($query);
                    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                    } else {
                    //If not, creating new row...
                        $msg .= " Not found old record, creating new... ";
                        $msg .= " Getting Moon Mineral type... ";
                        $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='$typeID' LIMIT 1";
                        $result = mysql_query($query);
                        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error());
                        $typeName = mysql_result($result, 0);
                        $msg .= " Associate with pos... ";
                        $query = "SELECT `posID` FROM  `poslist` WHERE `locationID`='$locationID' LIMIT 1";
                        $result = mysql_query($query);
                        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error());
                        $posID = mysql_result($result, 0);
                        $query = "INSERT INTO `silolist` SET `locationID`= '$locationID', `itemID` = '$itemID', `typeID` = '$typeID', `quantity` = '$quantity', `mmname` = '$typeName', `posID` = '$posID'";
                        $result = mysql_query($query);
                        if(!mysql_error()) $msg .= " Successful created"; else endlog($msg . mysql_error());
                    };
                }
            endforeach;
        }
        endlog($msg);
?>
