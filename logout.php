<?php
session_start();
    require_once 'init.php';
    require_once 'db_con.php';
    $db->openConnection();
    $SID = session_id();
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $result = $db->query($query);
    if ($db->countRows($result) != 1) {
        echo "GO AWAY";
    } else {
        session_regenerate_id();
        setcookie(SID, $cookieSID, time()-60*60*24*30);
        $loggedIN = 0;
    }
?>
<script type="text/javascript">
var delay = 500;
  setTimeout("document.location.href='/index.php'", delay);
</script>