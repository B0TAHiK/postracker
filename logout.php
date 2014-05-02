<?php
session_start();
require'db_con.php';
    require_once 'sane.php';
    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());
    $SID = session_id();
    $cookieSID = sanitizeMySQL($_COOKIE[SID]);
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $result = mysql_query($query);
    if (mysql_num_rows($result) != 1) {
        echo "GO AWAY";
    } else {
        session_regenerate_id();
        setcookie(SID, $cookieSID, time()-60*60*24*30);
        $loggedIN = 0;
    }
mysql_close();
?>
<script type="text/javascript">
var delay = 500;
  setTimeout("document.location.href='/'", delay);
</script>