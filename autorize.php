<?php
    session_start();
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
        $row = mysql_fetch_assoc($result);
        $groupID = $row[groupID];
        $query = "UPDATE `users` SET `lastSeen` = NOW() WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID'";
        $result = mysql_query($query) or DIE(mysql_error());
        if ($groupID == 1 || $groupID == 2 || $groupID == 3) {
            $_SESSION['corporationID'] = $row[corporationID];
            $loggedIN = 1;
            $_SESSION['groupID'] = $groupID;
            
        } else {
            setcookie(SID, $cookieSID, time()-60*60*24*30);
            $loggedIN = 0;
            $_SESSION['groupID'] = $groupID;
        }       
    }
    mysql_close();
    ?>
