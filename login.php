<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link rel="stylesheet" type="text/css" href="blocs/style.css">
        <link rel="stylesheet" type="text/css" href="blocs/navigation.css">
        <title>Log in</title>
    </head>
    <body>
        <?php
        require_once 'db_con.php';
        require_once 'sane.php';
        $thisPage="login";
        echo "<div id=\"all\">";
//        include 'blocs/header.php';
//        include "blocs/navigation.php";
        If (isset($_SESSION[id])) {
            echo "You already logged in!";
        } else {
            If (!isset($_POST[login]) OR !isset($_POST[password])) {
        echo<<<_END
        <table>
            <form action="login.php" method="post">
            <fieldset>
            <legend style="font-weight: bold">Login form</legend>
            Login:
            <input type="text" name="login"><br>
            Password:
            <input type = "password" name = "password">
            <br>
            <input type=submit>
            </fieldset>
            </form>
        </table>      
_END;
            } else {
                $login = sanitizeMySQL($_POST['login']);
                $password = md5($_POST['password']);
                mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
                mysql_select_db($db_name) or die(mysql_error());
                $query = "SELECT `id` FROM `users` WHERE (`login`='$login' AND `pass`='$password') LIMIT 1";
                $result = mysql_query($query);
                print(mysql_error());
                If (mysql_num_rows($result) == 1) {
                    $row = mysql_fetch_assoc($result);
                    $_SESSION['id']= $row['id'];
                    $_SESSION['login']= $login;
                    echo "You logged in!";
                } else {
                    echo "Wrong login or password!";
                };
            };
        };
        ?>
        </div>
    </body>
</html>
