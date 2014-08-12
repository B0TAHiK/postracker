<?php
$thisPage="settings";
require_once 'autorize.php';
require_once 'init.php';
include 'header.php';
If ($loggedIN === 1){
    //Requiring some libs...
    require_once 'db_con.php';
    
    if ($_POST[go] === 'sent') {
        $notifEmail = $_POST[email];
        $places = 1;
        $notifJabber = $_POST[jabber] << $places;
        $notifBinary = $notifEmail + $notifJabber;
        $JID = array ($_POST[login], $_POST[server]);
        $JIDString = implode('@', $JID);
        $query = "UPDATE `users` SET `mailNotif` = '$notifBinary', `JID` = '$JIDString' WHERE `id` = '$_SESSION[id]'";
        $result = $db->query($query);
        if(gettype($result) === object OR $result === TRUE) {
            $toTemplate['settings']['success'] = '1';
        }
    }
    
    $query = "SELECT * FROM `users` WHERE `id` = '$_SESSION[id]' LIMIT 1";
    $result = $db->query($query);
    $userInfo = $db->fetchAssoc($result);
    $notifBinary = $userInfo[mailNotif];
    if ($notifBinary & 1) {
        $toTemplate['settings']['emailChecked'] = 'checked';
    }
    if ($notifBinary & 2) {
        $toTemplate['settings']['jabberChecked'] = 'checked';
    }
    
    $JID = explode('@', $userInfo[JID]);
    $toTemplate['settings']['jabberLogin'] = $JID[0];
    $toTemplate['settings']['jabberServer'] = $JID[1];
}
$scriptName = explode(".", basename(__FILE__));
$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/templates');
$twig = new Twig_Environment($loader, array(
'cache' => dirname(__FILE__) . '/cache',
));
$template = $twig->loadTemplate($scriptName[0] . '.tpl');
echo $template->render($toTemplate);