<?php
require_once'db_con.php';
mysql_connect($hostname, $username, $mysql_pass);
function sanitizeString($var)
{
    $var = stripslashes($var);
    $var = htmlentities($var);
    $var = strip_tags($var);
    return $var;
}

function sanitizeMySQL($var)
{
    require_once'db_con.php';
    $var = mysql_real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}
?>
