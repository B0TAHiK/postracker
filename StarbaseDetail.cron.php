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
            //$i=0; // ???
            $page = "https://api.eveonline.com/corp/Starbasedetail.xml.aspx";
            $idkind = "itemID";
            //Getting POS list...
            $msg .= "<br/>Getting POS list for key " . $keyID . "...";
            $query = "SELECT `posID` FROM `poslist` WHERE `ownerID` = '$ownerID'";
            $result = mysql_query($query);
            if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
            $posID = array();
            while($row = mysql_fetch_row($result)){
                $posID[] = $row[0];
            }
            //Running script for each POS...
            foreach ($posID as $posIDQuery):
                //Getting API information...
                $msg .= "<br/>Getting POS id " . $posIDQuery . " info...";
                $apiPosDetails = api_req($page, $keyID, $vCode, $idkind, $posIDQuery);
                $i=0;
                //Parsing XML...
                foreach ( $apiPosDetails->result->rowset->row as $row):
                    $data[$i] = array (
                        'posFuelID' => strval($row[typeID]),
                        'posFuelQuantity' => strval($row[quantity])
                    );
                    //Distinguishing fuel blocs from Strontium Clathrates...
                        if ($data[$i][posFuelID] == 16275) {
                        $msg .= "<br/>Strontium clathrates:";
                        //Getting CT type...
                        $msg .= " CT type... ";
                        $query = "SELECT * FROM `poslist` WHERE `posID`='$posIDQuery' LIMIT 1";
                        $result = mysql_query($query);
                        $table = mysql_fetch_assoc($result);
                        $type = $table[typeID];
                        $systemID = $table[locationID];
                        echo $systemID;
                        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                        //Calculating Estimated time...
                        $msg .= " Calculating Estimated time.";
                        $data[$i]['stront'] = $data[$i][posFuelQuantity];
                        $stront = $data[$i][stront];
                        $time = calc_stront_time($type, $stront, $systemID, $msg);
                        //Adding information to the DB...
                        $msg .= " Adding information to the DB... ";
                        $query = "UPDATE `poslist` SET `stront` = '$stront', `rfTime` = '$time' WHERE `posID`='$posIDQuery'";
                        $result = mysql_query($query);
                        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                    } else {
                        $msg .= "<br/>Fuel blocs: ";
                        //Getting CT type...
                        $msg .= " CT type... ";
                        $query = "SELECT `typeid` FROM `poslist` WHERE `posID`='$posIDQuery' LIMIT 1";
                        $result = mysql_query($query);
                        $type = mysql_result($result, 0);
                        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                        //Calculating Estimated time...
                        $msg .= " Calculating Estimated time.";
                        $data[$i]['fuel'] = $data[$i][posFuelQuantity];
                        $fuel = $data[$i][fuel];
                        $time = calc_fuel_time($type, $fuel, $msg);
                        //Adding information to the DB...
                        $msg .= " Adding information to the DB... ";
                        $query = "UPDATE `poslist` SET `fuel` = '$fuel', `time` = '$time' WHERE `posID`='$posIDQuery'";
                        $result = mysql_query($query);
                        if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                    }
                    $i++;
                endforeach;
                //Checking if POS is in Reinfoce Mode...
                $msg .= "<br/>Checking if POS is in Reinfoce Mode... ";
                $query = "SELECT `posID` FROM `poslist` WHERE `posID` = '$posIDQuery' AND `state` = '3'";
                $result = mysql_query($query);
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                $num = mysql_num_rows($result);
                if ($num === 1) {
                //If yes, setting amount of Strontium to 0...
                $msg .= " POS in RF, setting amount of Strontium to 0... ";
                $query = "UPDATE `poslist` SET `stront` = '0' WHERE `posID`='$posIDQuery'";
                $result = mysql_query($query);
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                }
                endforeach;
        }
        endlog($msg);
?>
