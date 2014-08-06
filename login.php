<?php
    $thisPage="login";
    require_once 'autorize.php';
    ob_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
        <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <title>Log in</title>
    </head>
    <body>
        <?php include 'header.php'; ?>
         <div class="container">
                <div class="page-header">
                    <h1>Login Form</h1>
                </div>
        <?php
        If ($loggedIN === 1){
            if ($_POST[go] != 'sent'):
                ob_end_flush();
                echo "<div class=\"alert alert-danger\" role=\"alert\"> You already logged in!</div>";
            endif;
        } else {
                if ($_POST[go] == 'sent') {
                    $email = $_POST['email'];
                    $password = md5($_POST['password']);
                    $db->openConnection();
                    $query = "SELECT `id` FROM `users` WHERE (`email`='$email' AND `password`='$password') LIMIT 1";
                    $result = $db->query($query);
                    If ($db->countRows($result) == 1) {
                        $lastSID = session_id();
                        $query = "UPDATE `users` SET `lastSID` = '$lastSID' WHERE `email` = '$email'";
                        $result = $db->query($query);
                        setcookie(SID, $lastSID, time()+60*60*24*30);
                        ob_end_flush();
                        echo<<<_END
                       <div class="alert alert-success" role="alert">You logged in. You will be redirected shortly.</div>
                        <script type="text/javascript">
                            var delay = 5000;
                            setTimeout("document.location.href='/pos'", delay);
                        </script>
_END;
                    } else {
                        ob_end_flush();
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Wrong login or password!</div>";
                    };
                };
                echo<<<_END
                    <form action="login.php" method="post" class="login" style="width: 33%;margin: 0 auto;">
                    <div class="form-group" width="25%">
                        <label for="inputEmail">Email</label>
                        <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password">
                    </div>
                    <input type=hidden name="go" value="sent">
                    <button type="submit" class="btn btn-primary" id="submit">Login</button>
                    </form>
_END;
            };
            ob_end_flush();
        ?>
         </div>
            <?php include "bottom.php"; ?>
    </body>
</html>
