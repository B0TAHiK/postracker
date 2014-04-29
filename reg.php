<?php
    session_start();
    $thisPage="reg";
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
        if (isset($_SESSION[id])) {
            echo "You already logged in!";
        } else {
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
                    <td><input type=submit></td>
                </tr>
            </table>
            <div class="results"></div>
            </form>
_END;
               
            };
            if ($_POST[go] == 'sent'):
                if ($_POST[email] == ""):
                    echo "Please give your e-mail!";
                    exit;
                endif;
                if ($_POST[password] == ""):
                    echo "Please set up password!";
                    exit;
                endif;
                if ($_POST[chars] == ""):
                    echo "Please push 'Get Characters' button!";
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
                    echo "There is user with e-mail " . $email . "!";
                    exit;
                else:
                    $query = "INSERT INTO `users` SET `email` = '$email', `password` = '$password', `keyID` = '$keyID', `vCode` = '$vCode', `char` = '$char'";
                    $result = mysql_query($query) or die(mysql_error());
                    if ($result) {
                        echo 'Successfully registered!';
                    }
                endif;             
            endif;
        ?>
        </div>
    </body>
</html>
