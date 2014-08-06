<?php
    $thisPage="settings";
    require_once 'autorize.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/navigation.css">
        <title>Settings</title>
    </head>
    <body>
        <div id="wrapper">
            <?php include 'header.php'; ?>
            <div id="topic"><span id="topic">settings</span></div>
            <div id="mainbody">
            <?php
            If ($loggedIN > 0){
                if ($_POST[go] != 'sent') {
                    $query = "SELECT * FROM `users` WHERE `characterID` = '$charInfo[characterID]' LIMIT 1";
                    $result = $db->query($query);
                    $userInfo = $db->fetchAssoc($result);
                    $mailNotif = $userInfo[mailNotif];
                    $JID = $userInfo[JID];
                    if ($mailNotif > 0) {
                        $selected = "selected";
                        $selectedNo = "";
                    } else {
                        $selected = "";
                        $selectedNo = "selected";
                    }
                    echo<<<_END
                        <form action="settings.php" method="post" class = "login">
                        <table width = 600>
                            <tr>
                                <td class="maintext">Do you wish to receive notifications via e-mail?</td>
                                <td>
                                <select name="mailNotif">
                                    <option value="yes" $selected>Yes</option>
                                    <option value="no" $selectedNo>No</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="maintext">Your jabber ID (Leave blank if you don't want to get them)</td>
                                <td><input type="text" name = "JID" value="$JID" size=32 /></td>
                            </tr>
                            <tr>
                                <input type=hidden name="go" value="sent">
                                <td></td>
                                <td><input id="submit" value="Update" type=submit  /></td>
                            </tr>
                        </table>    
                        </form>
_END;
                } else {
                    require_once 'sane.php';
                    $choiceNotif = $_POST[mailNotif];
                    $JID = sanitizeMySQL($_POST[JID]);
                    if ($choiceNotif == "no") {
                        $notif = 0;
                    } else {
                        $notif = $_SESSION[groupID];
                    }
                    $query = "UPDATE `users` SET `mailNotif` = '$notif', `JID` = '$JID' WHERE `characterID` = '$charInfo[characterID]'";
                    $result = $db->query($query);
                    if ($result) {
                        echo "<div class='error'>Information updated.</div>";
                        echo<<<_END
                            <script type="text/javascript">
                                var delay = 2000;
                                setTimeout("document.location.href='settings.php'", delay);
                            </script>             
_END;
                    } else {
                        print (mysql_error());
                    }
                }
            } else {
                    echo "<div class='error'>Access denied. Autorization required.</div>";
            }
            ?>
            </div>
        </div>
        <?php include "bottom.php"; ?>
    </body>
</html>
