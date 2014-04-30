<?php
    session_start();
    $thisPage="reg";
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
        <?php
        require_once 'functions.php';
        require_once 'db_con.php';
        require_once 'sane.php';
        include 'header.php';
        if (isset($_SESSION[id]) OR isset($_COOKIE[id])){ 
            echo "<div class='error'>You already logged in!</div>";
        } else {
            echo $_SESSION[id];
            echo<<<_END
            <form action="reg.php" method="post" class="reg">
            <span id="head">Registration form</span>
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
                    <td><input id="submit" type=submit disabled=true /></td>
                </tr>
            </table>
            <div class="results"></div>
            </form>
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
                    $query = "INSERT INTO `users` SET `email` = '$email', `password` = '$password', `keyID` = '$keyID', `vCode` = '$vCode', `char` = '$char'";
                    $result = mysql_query($query) or die(mysql_error());
                    if ($result) {
                        echo '<div class="error">Successfully registered!</div>';
                        $query = "SELECT `id` FROM `users` WHERE `email` = '$email'";
                        $result = mysql_query($query);
                        $_SESSION[id] = mysql_result($result, 0);
                        setcookie(id, $id, time()+60*60*24*30);
                        ob_end_flush();
                    }
                endif;             
            endif;
            include 'bottom.php'
        ?>
        </div>
    </body>
</html>
