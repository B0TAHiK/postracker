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
        <!--<link rel="stylesheet" type="text/css" href="blocs/navigation.css">-->
        <title>Registration</title>
    </head>
    <body>
        <div id="wrapper">
        <?php
        require_once 'functions.php';
        require_once 'db_con.php';
        require_once 'sane.php';
        if (isset($_SESSION[id])) {
            echo "You already logged in!";
        } else {
            echo<<<_END
            <form action="reg.php" method="post">
            <fieldset>
            <legend style="font-weight: bold">Login form</legend>
            E-mail:
            <input type="text" name="email"><br>
            Password:
            <input type = "password" name = "password">
            <br>
            keyID:
            <input type="text" name="keyID" id="keyID"><br>
            vCode:
            <input type="text" name="vCode" id="vCode"><br>
            <div class="results"></div>
            <input type="button" class="getChars" onclick="SendRequest()" Value="Get Characters" />
            <input type=submit>
            </fieldset>
            </form>
            </table> 
            </fieldset>
            </form>
_END;
               
            };
        $email = $_POST[email];
        $password = $_POST[password];
        $keyID = $_POST[keyID];
        $vCode = $_POST[vCode];
        ?>
        </div>
    </body>
</html>
