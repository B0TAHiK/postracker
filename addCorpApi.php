<?php
    $thisPage="index";
    require_once 'autorize.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/navigation.css">
        <title>Corporation API</title>
    </head>
    <body>
        <div id="wrapper">
            <?php include 'header.php'; ?>
            <div id="topic"><span id="topic">corp api adder</span></div>
            <div id="mainbody">
            <?php
                If ($loggedIN == 1){
                    if ($_POST[go] != 'sent') {
                    echo<<<_END
                        <form action="addCorpApi.php" method="post" class = "login">
                        <table width = 600>
                            <tr>
                                <td class="maintext">keyID:</td>
                                <td><input type="text" name="keyID" id="keyID" /></td>
                            </tr>
                            <tr>
                                <td class="maintext">vCode:</td>
                                <td><input type="text" name="vCode" id="vCode" size=64 /></td>
                            </tr>
                            <tr>
                                <input type=hidden name="go" value="sent">
                                <td></td>
                                <td><input id="submit" value="Add" type=submit  /></td>
                            </tr>
                        </table>    
                        </form>
_END;
                    } else {
                    require_once 'sane.php';
                    require_once 'init.php';
                    $keyID = sanitizeMySQL($_POST[keyID]);
                    $vCode = sanitizeMySQL($_POST[vCode]);
                    
                    $page = "https://api.eveonline.com/account/apiKeyInfo.xml.aspx";
                    $api = api::api_req($page, $keyID, $vCode, '', '', '', '');
                    if (!($api->xpath("/eveapi/error[@code]")) AND ($api->xpath("/eveapi/result/key[@type='Corporation']"))) {
                        $query = "SELECT * FROM `apilist` WHERE `keyID` = '$keyID' AND `vCode` = '$vCode' LIMIT 1";
                        $result = $db->query($query);
                        if ($db->countRows($result) == 0) {
                            $maskAPI = api::get_mask($keyID, $vCode);
                            if ($maskAPI & 655370 > 0 || $maskAPI & 35309576) {
                                $corporationName = $api->result->key->rowset->row->attributes()->corporationName;
                                $query = "INSERT INTO `apilist` SET `keyID` = '$keyID', `vCode` = '$vCode', `mask` = '$maskAPI', `corporation` = '$corporationName'";
                                $result = $db->query($query);
                                if ($result) {
                                echo "<div class='error'>API key added.</div>";
                                echo<<<_END
                                    <script type="text/javascript">
                                        var delay = 2000;
                                        setTimeout("document.location.href='/settings.php'", delay);
                                    </script>             
_END;
                                } else {
                                    print (mysql_error());
                                }
                            } else {
                                echo "<div class='error'>This key don't have enough permissions! (Mask: $maskAPI)";
                            }
                        } else {
                            echo "<div class='error'>There is API with keyID $keyID and vCode $vCode!</div>";
                        }
                    } else {
                        echo "<div class='error'>Bad API key!</div>";
                    }
                    
                    
//                    $query = "UPDATE `users` SET `mailNotif` = '$notif', `JID` = '$JID' WHERE `characterID` = '$charInfo[characterID]'";
//                    $result = $db->query($query);
//                    if ($result) {
//                        echo "<div class='error'>Information updated.</div>";
//                        echo<<<_END
//                            <script type="text/javascript">
//                                var delay = 2000;
//                                setTimeout("document.location.href='/settings.php'", delay);
//                            </script>             
//_END;
//                    } else {
//                        print (mysql_error());
//                    }
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
