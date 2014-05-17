<?php
    $thisPage="index";
    require_once 'autorize.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/navigation.css">
        <title>Pos Monitor</title>
    </head>
    <body>
        <div id="wrapper">
            <?php include 'header.php'; ?>
            <div id="topic"><span id="topic">pos monitor</span></div>
            <div id="mainbody">
            <?php
                If ($loggedIN === 1){
                    //Requiring some libs...
                    require_once 'db_con.php';
                    require_once 'functions.php';
                    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
                    mysql_select_db($db_name) or die(mysql_error());
                    //Getting corps...
                    switch ($_SESSION[groupID]) {
                        case 1:
                        $query = "SELECT `ownerID` FROM `poslist` WHERE `ownerID` = '$_SESSION[corporationID]'";
                            break;
                        case 2:
                        case 3:
                        $query = "SELECT `ownerID` FROM `poslist`";
                            break;
                    }
                    $result = mysql_query($query);
                    $owners = array();
                    while ($ownerlist = mysql_fetch_row($result)) {
                    $owners[] = $ownerlist[0]; 
                    }
                    $onwersCut = array_unique($owners);
                    if (!isset($_POST[anchored])) {
                        echo "<form action='index.php' method='post' align='right'><input type=hidden name='anchored' value='old'><input type=submit value='Show Anchored POSes' /></form>";
                    } else {
                         echo "<form action='index.php' method='post' align='right'><input type=submit value='Hide Anchored POSes' /></form>";
                    }
                    //Making list of corps
                    foreach ($onwersCut as $owner):
                        if (!isset($_POST[anchored])) {
                                $MoreQuery = "AND `state` > 2";
                            } else {
                                $MoreQuery = "";
                            }
                        //Getting information for each corp...
                        $query = "SELECT * FROM `poslist` WHERE `ownerID` = '$owner' $MoreQuery";
                        $result = mysql_query($query) or die(mysql_error());
                        $data = array();
                        while ($poslist = mysql_fetch_assoc($result)) {
                            $data[] = $poslist;
                        }
                        if (count($data) <= 0) {
                            continue;
                        }
                        $ownerName = $data[0][ownerName];
                        echo "Owner: <b>$ownerName</b>";
                        echo "<table id='pos'>";
                        echo<<<_END
                        <tr id="title">
                            <td width = 10%>System:</td>
                            <td width = 20%>Type:</td>
                            <td width = 15%>Moon:</td>
                            <td width = 10%>State:</td>
                            <td width = 15%>Fuel left<br>(Days and hours):</td>
                            <td width = 15%>Stront time left<br>(reinforce timer):</td>
                            <td width = 15%>Silo information</td>
                        </tr>
_END;
                        $i=0;
                        //Parsing each POS...
                        foreach ($data as $table):
                            $time = hoursToDays($table[time]);
                            $rftime = hoursToDays($table[rfTime]);
                            $locationName = explode(" ", $table[moonName]);
                            $typeTemp = explode(" ", $table[typeName]);
                            $posType = $typeTemp[0];
                            $posID = $table[posID];
                            
                            switch ($posType) {
                                case "Minmatar":
                                case "Angel":
                                case "Domination":
                                    $siloMax = "20000";
                                    break;
                                case "Caldari":
                                case "Guristas":
                                case "Dread":
                                    $siloMax = "20000";
                                    break;
                                case "Amarr":
                                case "True":
                                case "Dark":
                                case "Sansha":
                                case "Blood":
                                    $siloMax = "30000";
                                    break;
                                case "Gallente":
                                case "Shadow":
                                case "Serpentis":
                                    $siloMax = "40000";
                                    break;
                                default:
                                    $siloMax = "0";
                                    break;
                            }
                            
                            $query = "SELECT * FROM `silolist` WHERE `posID` = '$posID'";
                            $result = mysql_query($query);
                            $numSilo = mysql_num_rows($result);
                            if ($numSilo > 0) {
                                $silo = array();
                                while ($silolist = mysql_fetch_assoc($result)) {
                                    $silo[] = $silolist;
                                }
                                $j=0;
                                foreach ($silo as $siloContents) {
                                    $siloInfo[$j][quantity] = $siloContents[quantity];
                                    $siloInfo[$j][mmVol] = $siloContents[quantity]*$siloContents[mmvolume];
                                    $siloInfo[$j][mmname] = "$siloContents[mmname]";
                                    $SiloFraction = Round(($siloInfo[$j][mmVol] / $siloMax), 2);
                                    $siloInfo[$j][percent] = $SiloFraction;
                                    $siloInfo[$j][maximum] = $siloMax / $siloContents[mmvolume];
                                    $j++;
                                }
                            }
                            
                            if (!($i % 2)){
                                $isColored = "id=colored";
                            } else {
                                $isColored = "";
                            }
                            if ($table[time] < 48 || $table[state] == 3) {
                                $alert = "id='alert'";
                            } else {
                                $alert = "";
                            }
                            switch ($table[state]) {
                                case "4":
                                    $state = "Online";
                                    $inRF = "";
                                    break;
                                case "3":
                                    $state = "<b>Reinforced!</b>";
                                    $inRF = "id='alert'";
                                    break;
                                case "2":
                                    $state = "Onlining";
                                    $inRF = "";
                                    break;
                                case "1":
                                    $state = "Anchored / Offline";
                                    $inRF = "";
                                    break;
                                case "0":
                                    $state = "Unanchored";
                                    $inRF = "";
                                    break;
                                default:
                                    $state = "N/A";
                                    $inRF = "";
                                    break;
                            }
                            echo<<<_END
                                <tr $isColored>
                                    <td>$locationName[0]</td>
                                    <td>$table[typeName]</td>
                                    <td>$table[moonName]</td>
                                    <td $inRF>$state</td>
                                    <td $alert>$time[d]d $time[h]h</td>
_END;
                            if ($table[state] == 3) {
                                echo "<td $inRF>$table[stateTimestamp]</td>";
                            } else {
                                echo "<td>$rftime[d]d $rftime[h]h</td>";
                            }
                            echo "<td>";
                            if ($numSilo > 0) {
                                echo "<table align=center>";
                                foreach ($siloInfo as $silos) {
                                    if ($siloInfo[$j][percent] > 0.8) {
                                            $alert = "id='alert'";
                                        } else {
                                            $alert = "";
                                        }
                                    echo "<tr>";
                                    echo "<td>$silos[mmname]:</td>";
                                    echo "<td $alert>$silos[quantity]/$silos[maximum]</td>";
                                    echo "</tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "No silo";
                            }
                             echo "</td>";
                            echo"</tr>";
                            $i++;
                            unset($siloInfo);
                            endforeach;
                        echo "</table><br>";
                    endforeach;
                } else {
                    echo "<div class='error'>Access denied. Autorization required.</div>";
                }
            ?>
                </div>
        </div>
        <?php include "bottom.php"; ?>
    </body>
</html>
