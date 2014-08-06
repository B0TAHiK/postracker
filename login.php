<?php
    $thisPage="login";
    require_once 'autorize.php';
    ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/navigation.css">
        <title>Log in</title>
    </head>
    <body>
        <div id="wrapper">
            <?php include 'header.php'; ?>
            <div id="topic"><span id="topic">login form</span></div>
            <div id="mainbody">
        <?php
        require_once 'db_con.php';
        require_once 'sane.php';
        If ($loggedIN === 1){
            if ($_POST[go] != 'sent'):
                ob_end_flush();
                echo "<div class='error'> You already logged in!</div>";
            endif;
        } else {            
        echo<<<_END
        <form action="login.php" method="post" class="login">
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
                    $db->openConnection();
                    
                    $query = "SELECT `id` FROM `users` WHERE (`email`='$email' AND `password`='$password') LIMIT 1";
                    $result = $db->query($query);
                    print(mysql_error());
                    If ($db->countRows($result) == 1) {
                        $lastSID = session_id();
                        $query = "UPDATE `users` SET `lastSID` = '$lastSID' WHERE `email` = '$email'";
                        $result = $db->query($query);
                        print(mysql_error());
                        setcookie(SID, $lastSID, time()+60*60*24*30);
                        ob_end_flush();
                        echo<<<_END
                        <div class='error'>You logged in.<br>You will be redirected shortly.</div>
                        <script type="text/javascript">
                            var delay = 5000;
                            setTimeout("document.location.href='/pos'", delay);
                        </script>
_END;
                    } else {
                        ob_end_flush();
                        echo "<div class='error'>Wrong login or password!</div>";
                    };
                };
            };
            ob_end_flush();
        ?>
                </div>
            </div>
            <?php include "bottom.php"; ?>
    </body>
</html>
