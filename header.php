<?php
$toTemplate = array();
$active = " class=active";
if ($loggedIN == 0) {
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
    $db->openConnection();
    $cookieSID = $db->sanitizeMySQL($_COOKIE[SID]);
    $query = "SELECT * FROM `users` WHERE `lastSID` = '$SID' OR `lastSID` = '$cookieSID' LIMIT 1";
    $result = $db->query($query);
    $charInfo = $db->fetchAssoc($result);
    $groupID = $charInfo[groupID];
    $_SESSION['id'] = $charInfo[id];
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
}
$toTemplate['loggedIN'] = $loggedIN;
$toTemplate['groupID'] = $_SESSION[groupID];