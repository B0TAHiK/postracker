<?php
    session_start();
    require_once 'db_con.php';
    require_once 'init.php';
    $db->openConnection();
    $SID = session_id();
    $cookieSID = $db->sanitizeMySQL($_COOKIE[SID]);
    
    $rTime = date('Y-m-d H:i:s', $_SERVER[REQUEST_TIME]);
    $uPage = $_SERVER[REQUEST_URI];
    $uIP = $_SERVER[REMOTE_ADDR];
    $rReferer = $_SERVER[HTTP_REFERER];
    $userAgent = $_SERVER[HTTP_USER_AGENT];
    
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $resultAutorize = $db->query($query);
    $charInfo = $db->fetchAssoc($resultAutorize);
    $groupID = $charInfo[groupID];
    $char = $charInfo[char];
    
    $_SESSION['userID'] = $charInfo[ID];
    
    if ($db->CountRows($resultAutorize) != 1) {
        setcookie(SID, $cookieSID, time()-60*60*24*30);
        $loggedIN = 0;
    } else {
        $row = $db->fetchAssoc($resultAutorize);
        $query = "UPDATE `users` SET `lastSeen` = NOW() WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID'";
        $result = $db->query($query);
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
    $query = "INSERT INTO `logs` SET `requestTime` = '$rTime', `page` = '$uPage', `charName` = '$char', `groupID` = '$groupID', `loggedIN` = '$loggedIN', `IP` = '$uIP', `referer` = '$rReferer', `userAgent` = '$userAgent'";
    $result = $db->query($query);
    $db->closeConnection();
    ?>
