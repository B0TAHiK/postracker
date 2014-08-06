<?php
/**
 * Description of notifications
 *
 * @author Григорий
 */
class notifications {
    public function ParsingNotifText($text, $OwnerCorporationID, $OwnerAllianceID){
        $txtarr = yaml_parse($text);
        $db->openConnection();
        if(!mysql_error()) 
        if($OwnerCorporationID > 0){
            $instxt = api::api_req("https://api.eveonline.com/corp/CorporationSheet.xml.aspx", "", "", "corporationID", $OwnerCorporationID, "", "");
            array_push($txtarr[OwnerCorpName]=strval($instxt->result->corporationName));
            array_push($txtarr[OwnerCorpTicker]=strval($instxt->result->ticker));
        }
        if($OwnerAllianceID > 0){
            $instxt = api::api_req("https://api.eveonline.com/eve/AllianceList.xml.aspx", "", "", "", "", "", "");
            $allyName = $instxt->xpath("/eveapi/result/rowset/row[@allianceID=$OwnerAllianceID]/@name");
            $allyTicker = $instxt->xpath("/eveapi/result/rowset/row[@allianceID=$OwnerAllianceID]/@shortName");
            array_push($txtarr[OwnerAllyName]=(string)$allyName[0][0]);
            array_push($txtarr[OwnerAllyTicker]=(string)$allyTicker[0][0]);
        }
        if($txtarr[aggressorID]){
            $instxt = api::api_req("https://api.eveonline.com/eve/CharacterName.xml.aspx", "", "", "IDs", $txtarr[aggressorID], "", "");
            $aggressorName = $instxt->xpath("/eveapi/result/rowset/row[@characterID=$txtarr[aggressorID]]/@name");
            array_push($txtarr[aggressorName]=(string)$aggressorName[0][0]);
        }
        if($txtarr[corpID] || $txtarr[aggressorCorpID]){
            $instxt = ($txtarr[corpID]) ? api::api_req("https://api.eveonline.com/corp/CorporationSheet.xml.aspx", "", "", "corporationID", $txtarr[corpID], "", "") :
             api::api_req("https://api.eveonline.com/corp/CorporationSheet.xml.aspx", "", "", "corporationID", $txtarr[aggressorCorpID], "", "");
            array_push($txtarr[corpName]=strval($instxt->result->corporationName));
            array_push($txtarr[corpTicker]=strval($instxt->result->ticker));
        }
        if($txtarr[allianceID] || $txtarr[aggressorAllianceID]){
            $instxt = api::api_req("https://api.eveonline.com/eve/AllianceList.xml.aspx", "", "", "", "", "", "");
            $allyName = ($txtarr[allianceID]) ? $instxt->xpath("/eveapi/result/rowset/row[@allianceID=$txtarr[allianceID]]/@name") : $instxt->xpath("/eveapi/result/rowset/row[@allianceID=$txtarr[aggressorAllianceID]]/@name");
            $allyTicker = ($txtarr[allianceID]) ? $instxt->xpath("/eveapi/result/rowset/row[@allianceID=$txtarr[allianceID]]/@shortName") : $instxt->xpath("/eveapi/result/rowset/row[@allianceID=$txtarr[aggressorAllianceID]]/@shortName");
            array_push($txtarr[allyName]=(string)$allyName[0][0]);
            array_push($txtarr[allyTicker]=(string)$allyTicker[0][0]);
        }
        if($txtarr[typeID]){
            $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='$txtarr[typeID]' LIMIT 1";
            $result = $db->query($query);
            array_push($txtarr[typeName]=mysql_result($result, 0));
        }
        if($txtarr[wants]){
            for($i=0; $i < count($txtarr[wants]); $i++){
                $query = "SELECT `typeName` FROM  `invTypes` WHERE `typeID`='{$txtarr[wants][$i][typeID]}' LIMIT 1";
                $result = $db->query($query);
                array_push($txtarr[wants][$i][typeName]=mysql_result($result, 0));
            }
        }
        if($txtarr[moonID]){
            $query = "SELECT `itemName` FROM  `mapDenormalize` WHERE `itemID`='$txtarr[moonID]' LIMIT 1";
            $result = $db->query($query);
            array_push($txtarr[moonName]=mysql_result($result, 0));
        }
        if($txtarr[solarSystemID]){
            $query = "SELECT `solarSystemName` FROM  `mapSolarSystems` WHERE `solarSystemID`='$txtarr[solarSystemID]' LIMIT 1";
            $result = $db->query($query);
            array_push($txtarr[solarSystemName]=mysql_result($result, 0));
        }
        if($txtarr[planetID]){
            $query = "SELECT `itemName` FROM  `mapDenormalize` WHERE `itemID`='$txtarr[planetID]' LIMIT 1";
            $result = $db->query($query);
            array_push($txtarr[planetName]=mysql_result($result, 0));
        }
        return yaml_emit($txtarr);
    }

    public function GenerateMailText($type, $sentDate, $str){ // http://wiki.eve-id.net/APIv2_Char_Notifications_XML
        $mailtext = "\n" . $sentDate . " ";
        $strarr = yaml_parse($str);
        if($type == 76){
            $mailtext .= $strarr[typeName] . " low on resources on " . $strarr[moonName] . "\n";
            $mailtext .= "Owner: " . $strarr[corpName] . " [" . $strarr[corpTicker] . "] (" . $strarr[allyName] . " [" . $strarr[allyTicker] . "])" . "\n";
            for($i=0; $i < count($strarr[wants]); $i++){
                $mailtext .= "Remaining " . $strarr[wants][$i][quantity] . " " . $strarr[wants][$i][typeName] . "\n";
            }
        } elseif($type == 75 || $type == 80 || $type == 86 || $type == 87 || $type == 88){
            $locname = ($type == 75) ? $strarr[moonName] : $strarr[solarSystemName];
            $mailtext .= $strarr[typeName] . " on " . $locname . " is under attack\n";
            $mailtext .= "Owner: " . $strarr[OwnerCorpName] . " [" . $strarr[OwnerCorpTicker] . "] (" . $strarr[OwnerAllyName] . " [" . $strarr[OwnerAllyTicker] . "])" . "\n";
            $mailtext .=  "Aggressor: " . $strarr[aggressorName] . " from " . $strarr[corpName] . " [" . $strarr[corpTicker] . "] (" . $strarr[allyName] . " [" . $strarr[allyTicker] . "])" . "\n";
            $mailtext .= "Shield: " . round($strarr[shieldValue]*100) . "% Armor: " . round($strarr[armorValue]*100) . "% Hull: " . round($strarr[hullValue]*100) . "%\n";
        } elseif($type == 93){
            $mailtext .= $strarr[typeName] . " on " . $strarr[planetName] . " is under attack\n";
            $mailtext .= "Owner: " . $strarr[OwnerCorpName] . " [" . $strarr[OwnerCorpTicker] . "] (" . $strarr[OwnerAllyName] . " [" . $strarr[OwnerAllyTicker] . "])" . "\n";
            $mailtext .=  "Aggressor: " . $strarr[aggressorName] . " from " . $strarr[corpName] . " [" . $strarr[corpTicker] . "] (" . $strarr[allyName] . " [" . $strarr[allyTicker] . "])" . "\n";
            $mailtext .= "Shield: " . round($strarr[shieldLevel]*100) . "%\n";
        } elseif($type == 77){
            $mailtext .= $strarr[typeName] . " in " . $strarr[solarSystemName] . " is under attack\n";
            $mailtext .= "Owner: " . $strarr[OwnerCorpName] . " [" . $strarr[OwnerCorpTicker] . "] (" . $strarr[OwnerAllyName] . " [" . $strarr[OwnerAllyTicker] . "])" . "\n";
            $mailtext .= "Shield: " . round($strarr[shieldLevel]*100) . "%\n";
        } else{
            if($type == 37 || $type == 38) $mailtext .= "Sovereignty claim fails in " . $strarr[solarSystemName] . "\n";
            if($type == 39 || $type == 40) $mailtext .= "Sovereignty bill late in " . $strarr[solarSystemName] . "\n";
            if($type == 41 || $type == 42) $mailtext .= "Sovereignty claim lost in " . $strarr[solarSystemName] . "\n";
            if($type == 43 || $type == 44) $mailtext .= "Sovereignty claim acquired in " . $strarr[solarSystemName] . "\n";
            if($type == 45) $mailtext .= "Control tower anchored in " . $strarr[solarSystemName] . "\n";
            if($type == 46) $mailtext .= "Alliance structure turns vulnerable in " . $strarr[solarSystemName] . "\n";
            if($type == 47) $mailtext .= "Alliance structure turns invulnerable in " . $strarr[solarSystemName] . "\n";
            if($type == 48) $mailtext .= "Sovereignty disruptor anchored in " . $strarr[solarSystemName] . "\n";
            if($type == 78) $mailtext .= "Station state change in " . $strarr[solarSystemName] . "\n";
            if($type == 79) $mailtext .= "Station conquered in " . $strarr[solarSystemName] . "\n";
        }
        return $mailtext;
    }
    public function sendmail($email, $subj, $text){
        $subject  = '=?UTF-8?B?' . base64_encode($subj) . '?=';
        $headers  = "MIME-Version: 1.0\r\n"; 
        $headers .= "Content-type: text/plain; charset=utf-8\r\n";
        $headers .= "From: POS tracker notification services <mailer@buaco.ru>\r\n";
        $headers .= "Reply-to: No-Reply <no_reply@buaco.ru>\r\n";
        return mail($email, $subject, $text, $headers);
    }
}