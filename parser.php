<?php
if(!isset($_GET['keyID']) || empty($_GET['keyID']))
            {
                    echo "keyID is not set or is empty";
                    return;
            }
 
            if(!isset($_GET['vCode']) || empty($_GET['vCode']))
            {
                    echo "keyID is not set or is empty";
                    return;
            }
            $page = "https://api.eveonline.com/corp/StarbaseList.xml.aspx";
            $keyID = $_GET['keyID'];
            $vCode = $_GET['vCode'];     

            require 'api_function.php';
            $api = api_req($page, $keyID, $vCode, '', '');
            require 'db_con.php';
            
            $i=0;
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
            
            mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
            mysql_select_db($db_name) or die(mysql_error());
            
            $i=0;
            for ($i = 0; $i < count($data); $i++) {
                $moonID = $data[$i][moonID];
                $typeID = $data[$i][typeID];
                
                $query = "SELECT `itemName` FROM  `mapDenormalize` WHERE `itemID`='$moonID' LIMIT 30";
                $result = mysql_query($query);
                print(mysql_error());
                $data[$i]['moonName'] = mysql_result($result, 0);
                
                $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='$typeID' LIMIT 30";
                $result = mysql_query($query);
                print(mysql_error());
                $data[$i]['typeName'] = mysql_result($result, 0);     
                
                foreach ($data[$i] as $row) {
                echo $row, "<br>";    
            };
            echo "<br>";
            }
            
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
                
                $query = "SELECT `posID` FROM `poslist`";
                $result = mysql_query($query);
                print(mysql_error());
                while($poslist = mysql_fetch_array($result , MYSQL_NUM)){
                for($j=0;$j<=count($data);$j++){
                    if($data[$j]['posID']==$poslist[0]) break;
                    if($j==count($data)){
			mysql_query("DELETE FROM `poslist` WHERE `posID`='{$poslist[0]}'");
			if( !mysql_error() ); else msg("[" . mysql_error() . "]");
                    }
                }
                }
                $query = "SELECT `posID` FROM `poslist` WHERE `posID`='$posID' LIMIT 1";
                $result = mysql_query($query);
                print(mysql_error());
                $num = mysql_num_rows($result);
                
                if ($num === 1) {
                    $query = "UPDATE `poslist` SET `state` = '$state', `stateTimestamp` = '$stateTimestamp' WHERE `posID`='$posID'";
                    $result = mysql_query($query);
                    print(mysql_error());
                } else {
                    $query = "INSERT INTO `poslist` SET `posID`= '$posID', `typeID` = '$typeID', `locationID` = '$locationID', `moonID` = '$moonID', `state` = '$state', `stateTimestamp` = '$stateTimestamp', `moonName` = '$moonName', `typeName` = '$typeName', `ownerID` = '$ownerID'";
                    $result = mysql_query($query);
                    print(mysql_error());
                };
            }            
?>