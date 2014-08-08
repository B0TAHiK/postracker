<?php
$thisPage="index";
require_once 'autorize.php';
require_once 'init.php';
include 'header.php';
If ($loggedIN === 1){
    //Requiring some libs...
    require_once 'db_con.php';
    $db->openConnection();
    //Getting corps...
    switch ($_SESSION[groupID]) {
        case 1:
        $query = "SELECT `ownerID` FROM `poslist` WHERE `ownerID` = '$_SESSION[corporationID]'";
            break;
        case 2:
        case 3:
        $query = "SELECT `ownerID` FROM `poslist`";
            break;
    }
    $result = $db->query($query);
    $owners = array();
    while ($ownerlist = $db->fetchRow($result)) {
    $owners[] = $ownerlist[0]; 
    }
    $onwersCut = array_unique($owners);
    
    $toTemplate['showAnchored'] = $_POST[anchored];
    //Making list of corps
    $corpCounter = 0; //1-st layer counter for $toTamplate[data] array
    foreach ($onwersCut as $owner):
        if (!isset($_POST[anchored])) {
                $MoreQuery = "AND `state` > 2";
            } else {
                $MoreQuery = "";
            }
        //Getting information for each corp...
        $query = "SELECT * FROM `poslist` WHERE `ownerID` = '$owner'" . $MoreQuery;
        $result = $db->query($query);
        $data = array();
        while ($poslist = $db->fetchAssoc($result)) {
            $data[] = $poslist;
        }
        if (count($data) <= 0) {
            continue;
        }
        $ownerName = $data[0][ownerName];
        $toTemplate['data'][$corpCounter]['corpName'] = $ownerName;
        $posCounter = 0; //2-st layer counter for $toTamplate[data][$corpCounter] array
        $i=0;
        //Parsing each POS...
        foreach ($data as $table):
            $time = posmonCalculations::hoursToDays($table[time]);
            $rftime = posmonCalculations::hoursToDays($table[rfTime]);
            $locationName = explode(" ", $table[moonName]);
            $typeTemp = explode(" ", $table[typeName]);
            $posType = $typeTemp[0];
            $posID = $table[posID];

            switch ($posType) {
                case "Minmatar":
                case "Angel":
                case "Domination":
                    $siloMax = "20000";
                    break;
                case "Caldari":
                case "Guristas":
                case "Dread":
                    $siloMax = "20000";
                    break;
                case "Amarr":
                case "True":
                case "Dark":
                case "Sansha":
                case "Blood":
                    $siloMax = "30000";
                    break;
                case "Gallente":
                case "Shadow":
                case "Serpentis":
                    $siloMax = "40000";
                    break;
                default:
                    $siloMax = "0";
                    break;
            }

            $query = "SELECT * FROM `silolist` WHERE `posID` = '$posID'";
            $result = $db->query($query);
            $numSilo = $db->countRows($result);
            if ($numSilo > 0) {
                $silo = array();
                while ($silolist = $db->fetchAssoc($result)) {
                    $silo[] = $silolist;
                }
                $j=0;
                foreach ($silo as $siloContents) {
                    $siloInfo[$j][quantity] = $siloContents[quantity];
                    $siloInfo[$j][mmVol] = $siloContents[quantity]*$siloContents[mmvolume];
                    $siloInfo[$j][mmname] = "$siloContents[mmname]";
                    $SiloFraction = Round(($siloInfo[$j][mmVol] / $siloMax), 2);
                    $siloInfo[$j][percent] = $SiloFraction;
                    $siloInfo[$j][maximum] = $siloMax / $siloContents[mmvolume];
                    $j++;
                }
            }
            if ($table[time] < 48 || $table[state] == 3) {
                $alert = "id='alert'";
            } else {
                $alert = "";
            }
            switch ($table[state]) {
                case "4":
                    $state = "Online";
                    $inRF = "";
                    break;
                case "3":
                    $state = "<b>Reinforced!</b>";
                    $inRF = "id='alert'";
                    break;
                case "2":
                    $state = "Onlining";
                    $inRF = "";
                    break;
                case "1":
                    $state = "Anchored / Offline";
                    $inRF = "";
                    break;
                case "0":
                    $state = "Unanchored";
                    $inRF = "";
                    break;
                default:
                    $state = "N/A";
                    $inRF = "";
                    break;
            }
            $toTemplate['data'][$corpCounter][$posCounter]['locationName'] = $locationName[0];
            $toTemplate['data'][$corpCounter][$posCounter]['typeName'] = $table[typeName];
            $toTemplate['data'][$corpCounter][$posCounter]['state'] = $state;
            $toTemplate['data'][$corpCounter][$posCounter]['time'] = $time;
            $toTemplate['data'][$corpCounter][$posCounter]['stateID'] = $table[state];
            $toTemplate['data'][$corpCounter][$posCounter]['moonName'] = $table[moonName];
            if ($table[state] == 3) {
                $toTemplate['data'][$corpCounter][$posCounter]['stateTimestamp'] = $table[stateTimestamp];
            } else {
                $toTemplate['data'][$corpCounter][$posCounter]['rftime'] = $rftime;
            }
            $toTemplate['data'][$corpCounter][$posCounter]['numSilo'] = $numSilo;
            if ($numSilo > 0) {
                $siloCounter = 0; //3-rd layer Silo Counter for $toTamplate[data][$corpCounter][$posCounter] array
                foreach ($siloInfo as $silos) {
                    if ($siloInfo[$j][percent] > 0.8) {
                        //alert
                    } else {
                        //noalert
                    }
                    $toTemplate['data'][$corpCounter][$posCounter][$siloCounter]['mmname'] = $silos[mmname];
                    $toTemplate['data'][$corpCounter][$posCounter][$siloCounter]['quantity'] = $silos[quantity];
                    $toTemplate['data'][$corpCounter][$posCounter][$siloCounter]['maximum'] = $silos[maximum];
                    $siloCounter++;
                }
            }
            $i++;
            $posCounter++;
            unset($siloInfo);
        endforeach;
        $corpCounter++;
    endforeach;
} else {
//    GO AWAY
}
$scriptName = explode(".", basename(__FILE__));
$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/templates');
$twig = new Twig_Environment($loader, array(
    'cache' => dirname(__FILE__) . '/cache',
));
$template = $twig->loadTemplate($scriptName[0] . '.tpl');
echo $template->render($toTemplate);