<?php
    echo "ХУЙ"; 
    $keyID = $_POST[keyID];
    $vCode = $_POST[vCode];
    $page = "https://api.eveonline.com/account/apikeyinfo.xml.aspx";
    $api = api_req($page, $keyID, $vCode, '', '');
    $i = 0;
    foreach ($api->result->key->rowset->row as $row):
        $data[$i] = array (
            'characterName' => $row[characterName],
            'characterID' => $row[characterID],
            'corporationID' => $row[corporationID],
            'corporationName' => $row[corporationName]
        );
    echo "<option value='", $data[$i][characterName], "'>", $data[$i][characterName], "'</option>";
    $i++;
    endforeach;
        ?>