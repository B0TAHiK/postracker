<?php

require_once dirname(__FILE__) . '/../db_con.php';
require_once dirname(__FILE__) . '/../init.php';

$db->openConnection();


$query = "SELECT * FROM `logs` WHERE `requestTime` > DATE_SUB( NOW( ) , INTERVAL 5 MINUTE)";
$result = $db->query($query);
$i=0;

while ($logs = $db->fetchAssoc($result)) {
    $logGroupID = $logs[groupID];
    $logLoggedIN = $logs[loggedIN];
    if ($logGroupID > 0 AND $logLoggedIN == 0) {
        
        $id = $logs[id];
        $char = $logs[charName];
        $IP = $logs[IP];
        $time = $logs[requestTime];
        $page = $logs[page];
        $refer = $logs[referer];
        $i++;
        $textTemp = "We are FUCKED!\nWe were hacked from IP $IP, char [$char] @ $time. Hacker's seen page $page, coming from $refer. ID #$id\n\n\n";
        $text = $text . $textTemp;
    }
}
if ($i>0) {
    $subj = "SECURITY BREACH!!!";
    $adminGroupID = 3;
    $query = "SELECT `email` FROM `users` WHERE `groupID` = '$adminGroupID'";
    $result = $db->query($query);
    while ($email = $db->fetchRow($result)) {
    sendmail($email[0], $subj, $text);
    }
}

?>
