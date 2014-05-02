<?php
    session_start();
    $thisPage="index";
    require_once 'sane.php';
    require_once 'db_con.php';
    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());
    $SID = session_id();
    $cookieSID = sanitizeMySQL($_COOKIE[SID]);
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) != 1) {
        setcookie(SID, $cookieSID, time()-60*60*24*30);
        $loggedIN = 0;
    } else {
        $loggedIN = 1;
    }
    mysql_close();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>Main Page</title>
    </head>
    <body>
        <div id="wrapper">
            <?php include 'header.php'; ?>
            <div id="topic"><span id="topic">main page</span></div>
            <div id="mainbody">
            <?php
                If ($loggedIN === 1){
                    require_once 'db_con.php';
                    require_once 'functions.php';
                    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
                    mysql_select_db($db_name) or die(mysql_error());
                    $query = "SELECT `ownerID` FROM `poslist`";
                    $result = mysql_query($query);
                    $owners = array();
                    while ($ownerlist = mysql_fetch_row($result)) {
                    $owners[] = $ownerlist[0]; 
                    };
                    $onwersCut = array_unique($owners);
                    foreach ($onwersCut as $owner):
                        $query = "SELECT * FROM `poslist` WHERE `ownerID` = $owner";
                        $result = mysql_query($query) or die(mysql_error());
                        $data = array();
                        while ($poslist = mysql_fetch_assoc($result)) {
                            $data[] = $poslist;
                        };
                        $ownerName = $data[0][ownerName];
                        echo "Owner: <b>$ownerName</b>";
                        echo "<table id='pos'>";
                        echo<<<_END
                        <tr>
                            <td><b>System:</b></td>
                            <td><b>Type:</b></td>
                            <td><b>Moon:</b></td>
                            <td><b>State:</b></td>
                            <td><b>Fuel left<br>(Days and hours):</b></td>
                            <td><b>Stront time left:</b></td>
                        </tr>
_END;
                        $i=0;
                        foreach ($data as $table):
                            $time = hoursToDays($table[time]);
                            $rftime = hoursToDays($table[rfTime]);
                            $locationName = explode(" ", $table[moonName]);
                            if (!($i % 2)){
                                $isColored = "id=colored";
                            } else {
                                $isColored = "";
                            };
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
                                    <td>$rftime[d]d $rftime[h]h</td>
                                </tr>
                        
_END;
                        
                            $i++;
                            endforeach;
                    echo "</table>";   
                    endforeach;
                } else {
                    echo "<div class='error'>Access denied. Autorization required.</div>";
                };
            ?>
            </div>
        <?php include "bottom.php"; ?>
        </div>
    </body>
</html>
