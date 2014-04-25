<?php
        //Requiring some libs...
        require 'db_con.php';
        require 'api_function.php';
        //Connecting to DB...
        mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
        mysql_select_db($db_name) or die(mysql_error());
        //StarbaseList parsing...
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
        //Running script for each API...
        for ($k = 0; $k < count($keyIDarr); $k++) {
            //Getting XML...
            $api = api_req($page, $keyID, $vCode, '', '');
            $i=0;
            //Parsing XML...
            foreach ( $api->result->rowset->row as $row):
                $data[$i] = array(
                    'posID' => strval($row[itemID]),
                    'typeID' => strval($row[typeID]),
                    'locationID' => strval($row[locationID]),
                    'moonID' => strval($row[moonID]),
                    'state' => strval($row[state]),
                    'stateTimestamp' => strval($row[stateTimestamp]),
                    'ownerID' => strval($row[standingOwnerID])
                );
            $i++;
            endforeach;

            $i=0;
            //Getting names from CCP MySQL DB...
            for ($i = 0; $i < count($data); $i++) {
                $moonID = $data[$i][moonID];
                $typeID = $data[$i][typeID];
                //Getting moon coordinates...
                $query = "SELECT `itemName` FROM  `mapDenormalize` WHERE `itemID`='$moonID' LIMIT 1";
                $result = mysql_query($query);
                print(mysql_error());
                $data[$i]['moonName'] = mysql_result($result, 0);
                //Getting CT type...
                $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='$typeID' LIMIT 1";
                $result = mysql_query($query);
                print(mysql_error());
                $data[$i]['typeName'] = mysql_result($result, 0);     
                // Comment next 5 strings if you don't wish to have debug information on the screen.
                foreach ($data[$i] as $row) {
                echo $row, "<br>";    
            };
            echo "<br>";
            }
            //Adding information to the DB...
            $i=0;
            for ($i = 0; $i < count($data); $i++) {
                $moonID = $data[$i][moonID];
                $typeID = $data[$i][typeID];
                $posID = $data[$i][posID];
                $locationID = $data[$i][locationID];
                $state = $data[$i][state];
                $stateTimestamp = $data[$i][stateTimestamp];
                $moonName = $data[$i][moonName];
                $typeName = $data[$i][typeName];
                $ownerID = $data[$i][ownerID];
                //Checking for obsolete records...
                $query = "SELECT `posID` FROM `poslist`";
                $result = mysql_query($query);
                print(mysql_error());
                while($poslist = mysql_fetch_array($result , MYSQL_NUM)){
                    for($j=0;$j<=count($data);$j++){
                        if($data[$j]['posID']==$poslist[0]) break;
                        if($j==count($data)){
                            //Deleting obsolete records, if found...
                            mysql_query("DELETE FROM `poslist` WHERE `posID`='{$poslist[0]}'");			
                        }
                    }
                }
                // Looking for old records...
                $query = "SELECT `posID` FROM `poslist` WHERE `posID`='$posID' LIMIT 1";
                $result = mysql_query($query);
                print(mysql_error());
                $num = mysql_num_rows($result);
                //If found, updating...
                if ($num === 1) {
                    $query = "UPDATE `poslist` SET `state` = '$state', `stateTimestamp` = '$stateTimestamp' WHERE `posID`='$posID'";
                    $result = mysql_query($query);
                    print(mysql_error());
                } else {
                //If not, creating new row...
                    $query = "INSERT INTO `poslist` SET `posID`= '$posID', `typeID` = '$typeID', `locationID` = '$locationID', `moonID` = '$moonID', `state` = '$state', `stateTimestamp` = '$stateTimestamp', `moonName` = '$moonName', `typeName` = '$typeName', `ownerID` = '$ownerID'";
                    $result = mysql_query($query);
                    print(mysql_error());
                };
            }
            //StarbaseList parsing finished, proceeding to StarbaseDetails...
            $page = "https://api.eveonline.com/corp/Starbasedetail.xml.aspx";
            $idkind = "itemID";
            //Getting POS list...
            $query = "SELECT `posID` FROM `poslist`";
            $result = mysql_query($query);
            print(mysql_error());
            $posID = array();
            while($row = mysql_fetch_row($result)){
                $posID[] = $row[0];
            }
            //Running script for each POS...
            foreach ($posID as $posIDQuery):
                //Getting API information...
                $api = api_req($page, $keyID, $vCode, $idkind, $posIDQuery);
                $i=0;
                //Parsing XML...
                foreach ( $api->result->rowset->row as $row):
                    $data[$i] = array (
                        'posFuelID' => strval($row[typeID]),
                        'posFuelQuantity' => strval($row[quantity])
                    );
                    //Distinguishing fuel blocs from Strontium Clathrates...
                    if ($data[$i][posFuelID] == 16275) {
                        //Getting CT type...
                        $query = "SELECT `typeid` FROM `poslist` WHERE `posID`='$posIDQuery' LIMIT 1";
                        $result = mysql_query($query);
                        $type = mysql_result($result, 0);
                        print(mysql_error());
                        //Calculating Estimated time...
                        $data[$i]['stront'] = $data[$i][posFuelQuantity];
                        $stront = $data[$i][stront];
                        $time = calc_stront_time($type, $stront);
                        //Adding information to the DB...
                        $query = "UPDATE `poslist` SET `stront` = '$stront', `rfTime` = '$time' WHERE `posID`='$posIDQuery'";
                        $result = mysql_query($query);
                        print(mysql_error());
                    } else {
                        //Getting CT type...
                        $query = "SELECT `typeid` FROM `poslist` WHERE `posID`='$posIDQuery' LIMIT 1";
                        $result = mysql_query($query);
                        $type = mysql_result($result, 0);
                        print(mysql_error());
                        //Calculating Estimated time...
                        $data[$i]['fuel'] = $data[$i][posFuelQuantity];
                        $fuel = $data[$i][fuel];
                        $time = calc_fuel_time($type, $fuel);
                        //Adding information to the DB...
                        $query = "UPDATE `poslist` SET `fuel` = '$fuel', `time` = '$time' WHERE `posID`='$posIDQuery'";
                        $result = mysql_query($query);
                        print(mysql_error());
                    }
                    $i++;
                endforeach;
                //Checking if POS is in Reinfoce Mode...
                $query = "SELECT `posID` FROM `poslist` WHERE `posID` = '$posIDQuery' AND `state` = '3'";
                $result = mysql_query($query);
                print(mysql_error());
                $num = mysql_num_rows($result);
                if ($num === 1) {
                //If yes, setting amount of Strontium to 0...
                $query = "UPDATE `poslist` SET `stront` = '0' WHERE `posID`='$posIDQuery'";
                $result = mysql_query($query);
                print(mysql_error());
                }
                endforeach;
        }
?>