<?php
$toTemplate = array();
if ($loggedIN == 0) {
    $signed = "unsigned";
    $active = " class=\"active\"";
    
    if ($thisPage === 'index') {
        $toTemplate['isIndex'] = $active;
    } else {
        $toTemplate['isIndex'] = '';
    }
    if ($thisPage === 'login') {
        $toTemplate['isLogin'] = $active;
    } else {
        $toTemplate['isLogin'] = '';
    }
    if ($thisPage === 'reg') {
        $toTemplate['isReg'] = $active;
    } else {
        $toTemplate['isReg'] = '';
    }
} else {
    
    $signed = "signed";
    
    $db->openConnection();
    $cookieSID = $db->sanitizeMySQL($_COOKIE[SID]);
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $result = $db->query($query);
    $charInfo = $db->fetchAssoc($result);
    $groupID = $charInfo[groupID];
    $query = "SELECT * FROM `groups` WHERE `id` = '$groupID' LIMIT 1";
    $result = $db->query($query);
    $groups = $db->fetchAssoc($result);
    $groupName = $groups[name];
    
    $toTemplate['charName'] = $charInfo[char];
    
    if ($thisPage === 'index') {
        $toTemplate['isIndex'] = $active;
    } else {
        $toTemplate['isIndex'] = '';
    }
    if ($thisPage === 'admin') {
        $toTemplate['isAdmin'] = $active;
    } else {
        $toTemplate['isAdmin'] = '';
    }
    if ($thisPage === 'supers') {
        $toTemplate['isSupers'] = $active;
    } else {
        $toTemplate['isSupers'] = '';
    }
    if ($thisPage === 'settings') {
        $toTemplate['isSettings'] = $active;
    } else {
        $toTemplate['isSettings'] = '';
    }
    
//        if ($thisPage=="index")echo " id=\"currentpage\"";
//          echo "><a href=\"/index.php\">pos monitor</a></li>";
//          if ($_SESSION[groupID] > 2) {
//            echo "<li";
//            if ($thisPage=="admin")
//            echo " id=\"currentpage\"";
//            echo"><a href=\"admin.php\">admin</a></li>";
//          }
//          if ($_SESSION[groupID] > 1) {
//            echo "<li";
//            if ($thisPage=="supers")
//            echo " id=\"currentpage\"";
//            echo"><a href=\"superCapitalMonitoring.php\">supercapitals</a></li>";
//          }
//          echo "<li";
//          if ($thisPage=="settings")echo " id=\"currentpage\"";
//          echo "><a href=\"settings.php\">settings</a></li>";
}



$tpl = new template();
$scriptName = explode(".", basename(__FILE__));
$tplName = "templates/" . $scriptName[0] ."." . $signed . ".tpl.php";
$tpl->SetTemplate("$tplName");
$tpl->AssignVar($toTemplate);
$tpl->Display();