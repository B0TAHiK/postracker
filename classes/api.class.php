<?php
set_time_limit(3600);
class api {
    public function api_req($page, $keyID, $vCode, $optionalParameterType1 = null, $optionalValue1 = null, $optionalParameterType2 = null, $optionalValue2 = null) {
        $request = $page . "?keyID=" . $keyID . "&vCode=" . $vCode;
        if ($optionalParameterType1) {
            $request = $request . "&" . $optionalParameterType1 . "=" . $optionalValue1;
        }
        if ($optionalParameterType2) {
            $request = $request . "&" . $optionalParameterType2 . "=" . $optionalValue2;
        }
        // create curl resource
        $ch = curl_init($request);
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

    public function get_mask($keyID, $vCode) {
        $page = "https://api.eveonline.com/account/apiKeyInfo.xml.aspx";
        $api = api::api_req($page, $keyID, $vCode, '', '', '', '');
        //$maskAPI = $api->result->key->attributes()->accessMask;
        $maskAPI = $api->xpath("/eveapi/result/key/@accessMask");
        return $maskAPI[0][0];
    }
    public function xml2array ($xmlObject, $out = array ()){
        foreach ((array) $xmlObject as $index => $node ) {
            $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
        }
        return $out;
    }
}