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
          echo "><a href=\"/\">main</a></li>"
       . "<li";
        if ($thisPage=="login")
            echo " id=\"currentpage\"";
            echo "><a href=\"login.php\">login</a></li>
            <li";
            if ($thisPage=="reg")
                echo " id=\"currentpage\"";
          echo "><a href=\"reg.php\">register</a></li>
            <li";
          if ($thisPage=="admin") 
          echo " id=\"currentpage\"";
          echo"><a href=\"admin.php\">admin</a></li>";
          echo "</ul>";
} else {
    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());
    $cookieSID = sanitizeMySQL($_COOKIE[SID]);
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    $charInfo = mysql_fetch_assoc($result);
     echo<<<_END
        <div id='head'><img class='logo' src=lg2.png>
        <span id='toptext'>POS tracker</span>
            <div id='logout'><a href="logout.php">Log Out</a></div>
                <div id='char'>
                <img src=https://image.eveonline.com/Character/$charInfo[characterID]_64.jpg>
                Welcome, <b>$charInfo[char]</b>!
            </div>
        </div>
        <ul id='bar'>
            <li
_END;
        if ($thisPage=="index")echo " id=\"currentpage\"";
          echo "><a href=\"/\">main</a></li>"
       . "<li";
          if ($thisPage=="admin") 
          echo " id=\"currentpage\"";
          echo"><a href=\"admin.php\">admin</a></li>";
          echo "</ul>";
}
?>
