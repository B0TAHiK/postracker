<?php
        //Requiring some libs...
        require_once 'functions.php';
        $msg = date(DATE_RFC822) . "<br/>[" . str_repeat("=",100) . "]";
        //Requiring some libs...
        require 'db_con.php';
        require_once 'functions.php';
        //Connecting to DB...
        $msg .= "<br/>Connecting to DB... ";
        mysql_connect($hostname, $username, $mysql_pass);
        if(!mysql_error()) {
            mysql_select_db($db_name);
            if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
        } else endlog($msg . mysql_error());
        //StarbaseList parsing...
        $msg .= "<br/>Collecting API keys... ";
        $page = "https://api.eveonline.com/corp/StarbaseList.xml.aspx";
        $query = "SELECT * FROM `apilist`";
        $result = mysql_query($query);
        //Getting APIs from DB...
        $keyIDarr = array();
        $vCodearr = array();
        while($row = mysql_fetch_assoc($result)){
                $keyIDarr[] = $row[keyID];
                $vCodearr[] = $row[vCode];                
        }
        if( count( $keyIDarr ) > 0 ) $msg .= " found " . count( $keyIDarr ) . " API keys"; else endlog($msg . " found none");
        //Running script for each API...
        for ($k = 0; $k < count($keyIDarr); $k++) {
            //Getting XML...
            //$msg .= "<br/>Parsing StarbaseList.xml for key " . $k+1;
            $keyID = $keyIDarr[$k];
            $vCode = $vCodearr[$k];
            $api = api_req($page, $keyID, $vCode, '', '');
            $i=0;
            //Parsing XML...
            $msg .= "<br/>Parsing POS ids for key " . $keyID;
            foreach ( $api->result->rowset->row as $row):
                $msg .= ", " . strval($row[itemID]);
                $data[$i] = array(
                    'posID' => strval($row[itemID]),
                    'typeID' => strval($row[typeID]),
                    'locationID' => strval($row[locationID]),
                    'moonID' => strval($row[moonID]),
                    'state' => strval($row[state]),
                    'stateTimestamp' => strval($row[stateTimestamp])
//                    'ownerID' => strval($row[standingOwnerID])
                );
            $i++;
            endforeach;

            //$i=0;
            //Getting names from CCP MySQL DB...
            for ($i = 0; $i < count($data); $i++) {
                $moonID = $data[$i][moonID];
                $typeID = $data[$i][typeID];
                //Getting moon coordinates...
                $msg .= "<br/>Moon id: " . $moonID . ". Getting coordinates... ";
                $query = "SELECT `itemName` FROM  `mapDenormalize` WHERE `itemID`='$moonID' LIMIT 1";
                $result = mysql_query($query);
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error()); 
                $data[$i]['moonName'] = mysql_result($result, 0);
                //Getting CT type...
                $msg .= " Getting CT type... ";
                $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='$typeID' LIMIT 1";
                $result = mysql_query($query);
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . "Error:" . mysql_error());
                $data[$i]['typeName'] = mysql_result($result, 0);     
                // Comment next 5 strings if you don't wish to have debug information on the screen.
                /*foreach ($data[$i] as $row) {
                echo $row, "<br>";    
                };
                echo "<br>";*/
            }
            //Adding information to the DB...
            //$i=0;
            for ($i = 0; $i < count($data); $i++) {
                $moonID = $data[$i][moonID];
                $typeID = $data[$i][typeID];
                $posID = $data[$i][posID];
                $locationID = $data[$i][locationID];
                $state = $data[$i][state];
                $stateTimestamp = $data[$i][stateTimestamp];
                $moonName = $data[$i][moonName];
                $typeName = $data[$i][typeName];
//                $ownerID = $data[$i][ownerID];
                //Checking for obsolete records...
                $msg .= "<br/>POS id " . $posID . ". Checking for obsolete records... ";
                $query = "SELECT `posID` FROM `poslist`";
                $result = mysql_query($query);
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                
                $pageChar = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
                $apiChar = api_req($pageChar, $keyID, $vCode, '', '');
                $ownerID = strval($apiChar->result->key->rowset->row->attributes()->corporationID);
                while($poslist = mysql_fetch_array($result , MYSQL_NUM)){
                    for($j=0;$j<=count($data);$j++){
                        if($data[$j]['posID']==$poslist[0]) break;
                        if($j==count($data)){
                            //Deleting obsolete records, if found...
                            mysql_query("DELETE FROM `poslist` WHERE `posID`='{$poslist[0]}' AND `ownerID` = '$ownerID'");			
                        }
                    }
                }
                // Looking for old records...
                $msg .= " Looking for old records... ";
                $query = "SELECT `posID` FROM `poslist` WHERE `posID`='$posID' LIMIT 1";
                $result = mysql_query($query);
                if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                $num = mysql_num_rows($result);
                //If found, updating...
                if ($num === 1) {
                    $msg .= " Found old record, updating... ";
                    $query = "UPDATE `poslist` SET `state` = '$state', `stateTimestamp` = '$stateTimestamp' WHERE `posID`='$posID'";
                    $result = mysql_query($query);
                    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                } else {
                //If not, creating new row...
                    $msg .= " Not found old record, creating new... ";
                    $query = "INSERT INTO `poslist` SET `posID`= '$posID', `typeID` = '$typeID', `locationID` = '$locationID', `moonID` = '$moonID', `state` = '$state', `stateTimestamp` = '$stateTimestamp', `moonName` = '$moonName', `typeName` = '$typeName', `ownerID` = '$ownerID'";
                    $result = mysql_query($query);
                    if(!mysql_error()) $msg .= "[ok]"; else endlog($msg . mysql_error());
                };
            }
            //StarbaseList parsing finished
        }
        endlog($msg);
?>
