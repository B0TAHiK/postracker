<?php
    function api_req($page, $keyID, $vCode, $kindid, $id) {
    // create curl resource
    $ch = curl_init($page . "?keyID=" . $keyID . "&vCode=" . $vCode . "&" . $kindid . "=" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // $response contains the XML response string from the API call
    $response = curl_exec($ch);
    // If curl_exec() fails/throws an error, the function will return false
    if($response === false){
    // Could add some 404 headers here
    return 'Curl error: ' . curl_error($ch);
    } else {
    $apiInfo = new SimpleXMLElement($response);
    return $apiInfo;
    }
    \curl_close($ch);
    // close curl resource to free up system resources  
    }
?>