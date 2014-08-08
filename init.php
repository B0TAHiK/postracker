<?php
spl_autoload_register("autoload");

function autoload($class_name)
{
    $baseDir = dirname(__FILE__);
    $fileName = "$baseDir/classes/$class_name.class.php";
    if (file_exists($fileName))
    {
        require_once $fileName;
        return;
    }
}


require_once '/Twig/Autoloader.php';
Twig_Autoloader::register();