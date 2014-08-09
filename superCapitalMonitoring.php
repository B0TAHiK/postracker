<?php
$thisPage="supers";
require_once 'autorize.php';
require_once 'init.php';
include 'header.php';
If ($loggedIN = 1 && $_SESSION[groupID] > 1){
    //Requiring some libs...
    require_once 'db_con.php';
    $db->openConnection();
    //Getting corps...
    $query = "SELECT `corporationName` FROM `superCapitalList`";
    $result = $db->query($query);
    $owners = array();
    while ($ownerlist = $db->fetchRow($result)) {
        $owners[] = $ownerlist[0]; 
    }
    
    $toTemplate['showOld'] = $_POST[showOld];
    $corpCounter = 0; //1-st layer counter for $toTamplate[data] array
    
    $onwersCut = array_unique($owners);
    foreach ($onwersCut as $owner):
        if (!isset($_POST[showOld])) {
            $MoreQuery = "AND `logoffDateTime` > DATE_SUB( NOW( ) , INTERVAL 3 MONTH)";
        } else {
            $MoreQuery = "";
        }
        $query = "SELECT * FROM `superCapitalList` WHERE `corporationName` = '$owner' $MoreQuery";
        $result = $db->query($query);
        $data = array();
        $i = 0;
        while ($superCapList = $db->fetchAssoc($result)) {
            $data[] = $superCapList;
            $i++;
        }
//        if ($i < 1) {
//            continue;
//        }
        $toTemplate['data'][$corpCounter]['corpName'] = $data[0][corporationName];
        $i = 0;
        $superCounter = 0; //2-st layer counter for $toTamplate[data][$corpCounter] array
        //Parsing each super...
        foreach ($data as $table):
            $toTemplate['data'][$corpCounter][$superCounter]['characterName'] = $table[characterName];
            $toTemplate['data'][$corpCounter][$superCounter]['shipTypeName'] = $table[shipTypeName];
            $toTemplate['data'][$corpCounter][$superCounter]['shipClass'] = $table[shipClass];
            $toTemplate['data'][$corpCounter][$superCounter]['locationName'] = $table[locationName];
            $toTemplate['data'][$corpCounter][$superCounter]['regionName'] = $table[regionName];
            $toTemplate['data'][$corpCounter][$superCounter]['SS'] = $table[SS];
            $toTemplate['data'][$corpCounter][$superCounter]['logonDateTime'] = $table[logonDateTime];
            $toTemplate['data'][$corpCounter][$superCounter]['logoffDateTime'] = $table[logoffDateTime];
//            echo "<td>$table[characterName]</td>";
//            echo "<td>$table[shipTypeName]</td>";
//            echo "<td>$table[shipClass]</td>";
//            echo "<td>$table[locationName]</td>";
//            echo "<td>$table[regionName]</td>";
//            echo "<td>$table[SS]</td>";
//            echo "<td>$table[logonDateTime]</td>";
//            echo "<td>$table[logoffDateTime]</td>";
            $i++;
            $superCounter++;
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
print_r($toTemplate);