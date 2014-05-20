<?php
    session_start();
    require_once 'sane.php';
    require_once 'db_con.php';
    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());
    $SID = session_id();
    $cookieSID = sanitizeMySQL($_COOKIE[SID]);
    
    $rTime = date('Y-m-d H:i:s', $_SERVER[REQUEST_TIME]);
    $uPage = $_SERVER[REQUEST_URI];
    $uIP = $_SERVER[REMOTE_ADDR];
    $rReferer = $_SERVER[HTTP_REFERER];
    $userAgent = $_SERVER[HTTP_USER_AGENT];
    
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $resultAutorize = mysql_query($query) or die(mysql_error());
    $charInfo = mysql_fetch_assoc($resultAutorize);
    $groupID = $charInfo[groupID];
    $char = $charInfo[char];
    $query = "INSERT INTO `logs` SET `requestTime` = '$rTime', `page` = '$uPage', `charName` = '$char', `groupID` = '$groupID', `IP` = '$uIP', `referer` = '$rReferer', `userAgent` = '$userAgent'";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($resultAutorize) != 1) {
        setcookie(SID, $cookieSID, time()-60*60*24*30);
        $loggedIN = 0;
    } else {
        $row = mysql_fetch_assoc($resultAutorize);
        $query = "UPDATE `users` SET `lastSeen` = NOW() WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID'";
        $result = mysql_query($query) or DIE(mysql_error());
        if ($groupID == 1 || $groupID == 2 || $groupID == 3) {
            $_SESSION['corporationID'] = $charInfo[corporationID];
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
