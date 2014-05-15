<?php
    $thisPage="supers";
    require_once 'autorize.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/scripts.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/navigation.css">
        <title>Supercapital Monitoring</title>
    </head>
    <body>
        <div id="wrapper">
            <?php include 'header.php'; ?>
            <div id="topic"><span id="topic">supercapital monitoring</span></div>
            <div id="mainbody">
                <?php
                    If ($loggedIN = 1 && $_SESSION[groupID] > 1){
                        //Requiring some libs...
                        require_once 'db_con.php';
                        require_once 'functions.php';
                        mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
                        mysql_select_db($db_name) or die(mysql_error());
                        //Getting corps...
                        $query = "SELECT `corporationName` FROM `superCapitalList`";
                        $result = mysql_query($query);
                        $owners = array();
                        while ($ownerlist = mysql_fetch_row($result)) {
                            $owners[] = $ownerlist[0]; 
                        }
                        $onwersCut = array_unique($owners);
                        if (!isset($_POST[old])) {
                            echo "<form action='supercapitalMonitoring.php' method='post' align='right'><input type=hidden name='old' value='old'><input type=submit value='Show old faggots' /></form>";
                        } else {
                            echo "<form action='supercapitalMonitoring.php' method='post' align='right'><input type=submit value='Hide old faggots' /></form>";
                        }
                        foreach ($onwersCut as $owner):
                            if (!isset($_POST[old])) {
                                $MoreQuery = "AND `logoffDateTime` > DATE_SUB( NOW( ) , INTERVAL 6 MONTH)";
                            } else {
                                $MoreQuery = "";
                            }
                            $query = "SELECT * FROM `superCapitalList` WHERE `corporationName` = '$owner' $MoreQuery";
                            $result = mysql_query($query) or die(mysql_error());
                            $data = array();
                            $i = 0;
                            while ($superCapList = mysql_fetch_assoc($result)) {
                                $data[] = $superCapList;
                                $i++;
                            }
                            if ($i < 1) {
                                continue;
                            }
                            $corporationName = $data[0][corporationName];
                            echo "Owner: <b>$corporationName</b>";
                            echo "<table id='pos'>";
                            echo<<<_END
                            <tr id="title">
                                <td width = 10%>Pilot:</td>
                                <td width = 10%>Ship:</td>
                                <td width = 15%>Class:</td>
                                <td width = 10%>System:</td>
                                <td width = 10%>Region:</td>
                                <td width = 5%>SS:</td>
                                <td width = 20%>Last Login:</td>
                                <td width = 20%>Last Logout:</td>
                            </tr>
_END;
                            $i = 0;
                            //Parsing each super...
                            foreach ($data as $table):
                                    if (!($i % 2)){
                                    $isColored = "id=colored";
                                } else {
                                    $isColored = "";
                                }
                                echo "<tr $isColored>";
                                echo "<td>$table[characterName]</td>";
                                echo "<td>$table[shipTypeName]</td>";
                                echo "<td>$table[shipClass]</td>";
                                echo "<td>$table[locationName]</td>";
                                echo "<td>$table[regionName]</td>";
                                echo "<td>$table[SS]</td>";
                                echo "<td>$table[logonDateTime]</td>";
                                echo "<td>$table[logoffDateTime]</td>";
                            $i++;
                            endforeach;
                            echo "</table>";
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
