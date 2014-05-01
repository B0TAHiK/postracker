<?php
    session_start();
    $thisPage="reg";
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
    if ($_POST[go] == 'sent'):
        ob_start();
    endif;
?>
<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/scripts.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>Registration</title>
    </head>
    <body>
        <div id="wrapper">
            <?php include 'header.php'; ?>
            <div id="topic"><span id="topic">registration form</span></div>
        <?php
        require_once 'functions.php';
        require_once 'db_con.php';
        require_once 'sane.php';
        if ($loggedIN === 1){ 
            echo "<div class='error'>You already logged in!</div>";
        } else {
            echo<<<_END
            <form action="reg.php" method="post" class="reg">
            <div id="mainbody">
             <table>
                <tr>
                    <td class="maintext">E-mail:</td>
                    <td><input type="text" name="email"></td>
                </tr>
                <tr>
                    <td class="maintext">Password:</td>
                    <td><input type = "password" name = "password"></td>
                </tr>
                <tr>
                    <td class="maintext">keyID:</td>
                    <td><input type="text" name="keyID" id="keyID"></td>
                </tr>
                <tr>
                    <td class="maintext">vCode:</td>
                    <td><input type="text" name="vCode" id="vCode" size=64> <input type="button" class="getChars" onclick="SendRequest()" Value="Get Characters" /></td>
                </tr>
                <tr>
                    <input type=hidden name="go" value="sent">
                    <td><input id="submit" value="Register" type=submit disabled=true /></td>
                </tr>
            </table>
            <div class="results"></div>
            </form></div>
_END;
               
            };
            if ($_POST[go] == 'sent'):
                if ($_POST[email] == ""):
                    echo "<div class='error'>Please give your e-mail!</div>";
                    ob_end_flush();
                    exit;
                endif;
                if ($_POST[password] == ""):
                    echo "<div class='error'>Please set up password!</div>";
                    ob_end_flush();
                    exit;
                endif;
                if ($_POST[chars] == ""):
                    echo "<div class='error'>Please push 'Get Characters' button!</div>";
                    ob_end_flush();
                    exit;
                endif;
                $email = sanitizeMySQL($_POST[email]);
                $password = md5($_POST[password]);
                $keyID = sanitizeMySQL($_POST[keyID]);
                $vCode = sanitizeMySQL($_POST[vCode]);
                $char = sanitizeMySQL($_POST[chars]);
                mysql_connect($hostname, $username, $mysql_pass);
                mysql_select_db($db_name);
                $query = "SELECT `email` FROM `users` WHERE `email`='$email' LIMIT 1";
                $result = mysql_query($query);
                if (mysql_num_rows($result) == 1):
                    echo "<div class='error'>There is user with e-mail <b>" . $email . "</b>!</div>";
                    ob_end_flush();
                    exit;
                else:
                    require_once 'functions.php';
                    $characterID = $_SESSION[$char][characterID];
                    $corporationID = $_SESSION[$char][corporationID];
                    $allianceID = $_SESSION[$char][allianceID];
                    $lastSID = session_id();
                    $query = "INSERT INTO `users` SET `email` = '$email', `password` = '$password', `keyID` = '$keyID', `vCode` = '$vCode', `char` = '$char', `characterID` = '$characterID', `corporationID` = '$corporationID', `allianceID` = '$allianceID', `lastSID` = '$lastSID'";
                    $result = mysql_query($query) or die(mysql_error());
                    if ($result) {
                        echo<<<_END
                        <div class="error">Successfully registered!<br>You will be redirected shortly.</div>
                        <script type="text/javascript">
                            var delay = 500;
                            setTimeout("document.location.href='/'", delay);
                        </script>
_END;
                        setcookie(SID, $lastSID, time()+60*60*24*30);
                        ob_end_flush();
                    }
                endif;             
            endif;
            include 'bottom.php'
        ?>
        </div>
    </body>
</html>
