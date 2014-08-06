<?php
/**
 * Description of posmonCalculations
 *
 * @author Григорий
 */
class posmonCalculations {
    public function calc_fuel_time($typeID, $systemID, $allyownerID, $msg) {
        include dirname(__FILE__) . '/../db_con.php';
        $config = new config($hostname, $username, $password, $database);
        $db = db::getInstance($config);
        $db->openConnection();
        $query = "SELECT `quantity` FROM `invControlTowerResources` WHERE  `controlTowerTypeID` = '$typeID'";
        $result = $db->query($query);
        $page = "https://api.eveonline.com/map/Sovereignty.xml.aspx";
        $row = $db->fetchAssoc($result);
        $api = api::api_req($page, "", "");
        $systemownerID = $api->xpath("/eveapi/result/rowset/row[@solarSystemID=$systemID]/@allianceID");
        $posTypeRow = $db->fetchRow($result);
        $posTypeID = $posTypeRow[0];
        $time = ($allyownerID != $systemownerID[0][0]) ? $posTypeID : $posTypeID*0.75;
        return $time;
    }

    public function calc_stront_time($typeID, $systemID, $stront, $allyownerID, $msg) {
        include dirname(__FILE__) . '/../db_con.php';
        $config = new config($hostname, $username, $password, $database);
        $db = db::getInstance($config);
        $db->openConnection();
        $query = "SELECT `quantity` FROM `invControlTowerResources` WHERE  `controlTowerTypeID` = '$typeID'";
        $result = $db->query($query);
        $page = "https://api.eveonline.com/map/Sovereignty.xml.aspx";
        $api = api::api_req($page, "", "");
        $systemownerID = $api->xpath("/eveapi/result/rowset/row[@solarSystemID=$systemID]/@allianceID");
        $posTypeRow = $db->fetchRow($result);
        $posTypeID = $posTypeRow[0];
        $rfTime = $stront / (($allyownerID != $systemownerID[0][0]) ? $posTypeID : $posTypeID*0.75);
        return floor($rfTime);
    }
    public function hoursToDays($inputTime) {
        $hoursInADay = 24;
        $days = floor($inputTime / $hoursInADay);
        $hoursLeft = $inputTime - $days * $hoursInADay;
        $result = array (
            'd' => $days,
            'h' =>$hoursLeft
        );
        return $result;
    }
}