<?php
    session_start();
    require_once 'functions.php';
    require'db_con.php';
    echo "<link rel='stylesheet' type='text/css' href='css/style.css'>";
    $keyID = $_POST[keyID];
    $vCode = $_POST[vCode];
    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
    mysql_select_db($db_name) or die(mysql_error());
    $mask = get_mask($keyID, $vCode);
    $maskNeeded = 49152;
    if (($mask & $maskNeeded) <= 0) {
        //No Access
        die("<div class='error'>Wrong Mask!</div>");
    }
    echo '<select name="chars" size="1">';
    $page = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
    $api = api_req($page, $keyID, $vCode, '', '');
    $i = 0;
    foreach ($api->result->key->rowset->row as $row):
        $data[$i] = array (
            'characterName' => $row[characterName],
            'characterID' => $row[characterID],
            'corporationID' => $row[corporationID],
            'corporationName' => $row[corporationName],
            'allianceName' => $row[allianceName],
            'allianceID' => $row[allianceID]
        );
    echo $data[$i][allianceName];
        if ($data[$i][allianceName] == ""):
            $data[$i][allianceName] = "None";
        endif;
    echo "<option value='", $data[$i][characterName], "'>", $data[$i][characterName], "</option>";
    $char = xml2array($data[$i][characterName]);
    $charID = xml2array($data[$i][characterID]);
    $corporationID = xml2array($data[$i][corporationID]);
    $allianceID = xml2array($data[$i][allianceID]);
    $_SESSION["$char[0]"] = array (
        'characterID' => $charID[0],
        'corporationID' =>  $corporationID[0],
        'allianceID' => $allianceID[0]
    );
    $query = "SELECT * FROM `allowedUsers` WHERE `characterID` = '$charID[0]' OR `corporationID`= '$corporationID[0]' OR `allianceID` = '$allianceID[0]' LIMIT 1";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) === 1) {
        $_SESSION["$char[0]"]["allowed"] = 1;
        $highlight[$i] = "id=allowed";
    } else {
        $_SESSION["$char[0]"]["allowed"] = 0;
        $highlight[$i] = "id=declined";
    }
    $i++;
    endforeach;
    echo<<<_END
        </select><br>
        <table id="charList">
_END;
    for ($i = 0; $i < count($data); $i++) {
    echo "<tr><td>Character: </td><td><span $highlight[$i]><b>", $data[$i][characterName], "</b></span><td>Corporation: </td><td><span $highlight[$i]><b>", $data[$i][corporationName], "</b></span></td><td>Alliance: </td><td><span $highlight[$i]><b>", $data[$i][allianceName], "</b></span></td>";
}
echo "</table>";
?>