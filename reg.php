<?php
    session_start();
    $thisPage="reg";
?>
<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
        <script type="text/javascript">
            function SendRequest(){
                $.ajax({
                    type: "POST",
                    url: "getChars.php",
                    data: "sid=<?=session_id()?>&keyID=565702&vCode=9MONZoBdlsvcv20hPZV21Bsx3Lo29XEz9TxHUnGgeR8vkGMmjsaSiSf35DsDxhfK",
                    success: function(html){
                        $('#response').html(html);
                    }
                });
                return false;
            };
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <!--<link rel="stylesheet" type="text/css" href="blocs/style.css">-->
        <!--<link rel="stylesheet" type="text/css" href="blocs/navigation.css">-->
        <title>Registration</title>
    </head>
    <body>
        <?php
        require_once 'functions.php';
        require_once 'db_con.php';
        require_once 'sane.php';
        if (isset($_SESSION[id])) {
            echo "You already logged in!";
        } else {
            echo<<<_END
            <div id="response"></div>
            <table>
            <form action="reg.php" method="post">
            <fieldset>
            <legend style="font-weight: bold">Login form</legend>
            E-mail:
            <input type="text" name="email"><br>
            Password:
            <input type = "password" name = "password">
            <br>
            keyID:
            <input type="text" name="keyID"><br>
            vCode:
            <input type="text" name="vCode"><br>
            <select name="menu" size="1">
            
            </select>
_END;
            if($apilist) {
             echo<<<_END
            <input type=submit>
            </fieldset>
            </form>
        </table>  
_END;
            } else {
               echo<<<_END
            <button onclick="SendRequest()">Get Characthers</button>
            </fieldset>
            </form>
        </table>  
_END;
               
            }
        }
        $email = $_POST[email];
        $password = $_POST[password];
        $keyID = $_POST[keyID];
        $vCode = $_POST[vCode];        
        ?>
    </body>
</html>
