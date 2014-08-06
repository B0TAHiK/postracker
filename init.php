<?php
function autoloader($class) {
    include 'classes/' . $class . '.class.php';
}
spl_autoload_register('autoloader');
require_once 'Twig/Autoloader.php';
Twig_Autoloader::register();