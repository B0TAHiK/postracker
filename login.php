<?php
    session_start();
    $thisPage="login";
    ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>Log in</title>
    </head>
    <body>
        <div id="wrapper">
        <?php
        require_once 'db_con.php';
        require_once 'sane.php';
        include 'header.php';
        If (isset($_SESSION[id]) OR isset($_COOKIE[id])){
            if ($_POST[go] != 'sent'):
                ob_end_flush();
                echo "<div class='error'> You already logged in!</div>";
            endif;
        } else {            
        echo<<<_END
        <form action="login.php" method="post" class="login">
            <span id="head">Login form</span>
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
                <td>
                    <input type=hidden name="go" value="sent">
                    <input id="submit" type=submit />
                </td>
                </tr>
            </table>
            </form>   
_END;
                if ($_POST[go] == 'sent') {
                    $email = sanitizeMySQL($_POST['email']);
                    $password = md5($_POST['password']);
                    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
                    mysql_select_db($db_name) or die(mysql_error());
                    $query = "SELECT `id` FROM `users` WHERE (`email`='$email' AND `password`='$password') LIMIT 1";
                    $result = mysql_query($query);
                    print(mysql_error());
                    If (mysql_num_rows($result) == 1) {
                        $row = mysql_fetch_assoc($result);
                        $_SESSION['id']= $row['id'];
                        setcookie(id, $id, time()+60*60*24*30);
                        ob_end_flush();
                        echo "<div class='error'>You logged in!</div>";
                    } else {
                        ob_end_flush();
                        echo "<div class='error'>Wrong login or password!</div>";
                    };
                };
            };
            ob_end_flush();
        include "bottom.php";
        ?>
        </div>
    </body>
</html>
