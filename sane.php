<?php
function sanitizeString($var)
{
    $var = stripslashes($var);
    $var = htmlentities($var);
    $var = strip_tags($var);
    return $var;
}

function sanitizeMySQL($var){
    require 'db_con.php';
    mysql_connect($hostname, $username, $mysql_pass) or die(mysql_error());
    $var = mysql_real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}
?>
