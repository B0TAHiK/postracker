<?php
session_start();
$thisPage="login";
require_once 'autorize.php';
require 'header.php';
If ($loggedIN === 1){
    if ($_POST[go] != 'sent'):
        $toTemplate['fromPost'] = '0';
    endif;
} else {
    if ($_POST[go] == 'sent') {
        $toTemplate['fromPost'] = '1';
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $db->openConnection();
        $query = "SELECT `id` FROM `users` WHERE (`email`='$email' AND `password`='$password') LIMIT 1";
        $result = $db->query($query);
        If ($db->countRows($result) == 1) {
            $lastSID = session_id();
            $query = "UPDATE `users` SET `lastSID` = '$lastSID' WHERE `email` = '$email'";
            $result = $db->query($query);
            setcookie(SID, $lastSID, time()+60*60*24*30);
            $toTemplate['success'] = '1';
        } else {
            $toTemplate['success'] = '0';
        };
    } else {
        $toTemplate['fromPost'] = '0';
    }
};
$scriptName = explode(".", basename(__FILE__));
$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/templates');
$twig = new Twig_Environment($loader, array(
    'cache' => dirname(__FILE__) . '/cache',
));
$template = $twig->loadTemplate($scriptName[0] . '.tpl');
echo $template->render($toTemplate);
print_r($toTemplate);