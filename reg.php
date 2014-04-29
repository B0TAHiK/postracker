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
        include 'header.php';
        if (isset($_SESSION[id])) {
            echo "You already logged in!";
        } else {
            echo<<<_END
            <form action="reg.php" method="post" class="reg">
            <td><legend style="font-weight: bold">Registration form</legend></td>
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
                    <td><input type=submit></td>
                </tr>
            </table>
            <div class="results"></div>
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
