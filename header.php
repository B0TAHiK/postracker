<?php
if ($loggedIN == 0) {
    echo<<<_END
        <div id='head'><img class='logo' src=lg2.png>
        <span id='toptext'>POS tracker</span>
        </div>
        <ul id='bar'>
            <li
_END;
        if ($thisPage=="index")echo " id=\"currentpage\"";
          echo "><a href=\"/postracker\">pos monitor</a></li>"
       . "<li";
        if ($thisPage=="login")
            echo " id=\"currentpage\"";
            echo "><a href=\"login.php\">login</a></li>
            <li";
            if ($thisPage=="reg")
                echo " id=\"currentpage\"";
          echo "><a href=\"reg.php\">register</a></li>";
          echo "</ul>";
} else {
    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());
    $cookieSID = sanitizeMySQL($_COOKIE[SID]);
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    $charInfo = mysql_fetch_assoc($result);
    $groupID = $charInfo[groupID];
    $query = "SELECT * FROM `groups` WHERE `id` = '$groupID' LIMIT 1";
    $result = mysql_query($query);
    $groupName = mysql_result($result, 0, $field = 1);
     echo<<<_END
        <div id='head'><img class='logo' src=lg2.png>
        <span id='toptext'>POS tracker</span>
            <div id='char'>
                <img src=https://image.eveonline.com/Character/$charInfo[characterID]_64.jpg>
                <span id='char'>
                    Welcome,<br>
                    $charInfo[char]!<br>
                    [$groupName]
                </span>
            </div>
        </div>
        <ul id='bar'>
            <li
_END;
        if ($thisPage=="index")echo " id=\"currentpage\"";
          echo "><a href=\"/postracker\">pos monitor</a></li>";
          if ($_SESSION[groupID] > 2) {
            echo "<li";
            if ($thisPage=="admin")
            echo " id=\"currentpage\"";
            echo"><a href=\"admin.php\">admin</a></li>";
          }
          if ($_SESSION[groupID] > 1) {
            echo "<li";
            if ($thisPage=="supers")
            echo " id=\"currentpage\"";
            echo"><a href=\"supercapitalMonitoring.php\">supercapitals</a></li>";
          }
          echo "<li><a href=\"https://redalliance.pw\">forum</a></li><li";
          echo "><a href=\"logout.php\">logout</a></li>";
          echo "</ul>";
}
?>
