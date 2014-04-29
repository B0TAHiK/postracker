<?php
    require_once 'functions.php';
    $keyID = $_POST[keyID];
    $vCode = $_POST[vCode];
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
            'allianceName' => $row[allianceName]
        );
    echo $data[$i][allianceName];
        if ($data[$i][allianceName] == ""):
            $data[$i][allianceName] = "None";
        endif;
    echo "<option value='", $data[$i][characterName], "'>", $data[$i][characterName], "</option>";
    $i++;
    endforeach;
    echo<<<_END
        </select><br>
        <ul id="charList">
_END;
    for ($i = 0; $i < count($data); $i++) {
    echo "Character: <b>", $data[$i][characterName], "</b> Corporation: <b>", $data[$i][corporationName], "</b> Alliance: <b>", $data[$i][allianceName], "</b><br>";
}
echo "</ul>"
?>