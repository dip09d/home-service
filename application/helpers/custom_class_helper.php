<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function autoloadCustomClass($className) {
    if($className=='ParagonIE_Sodium_Compat'){
        return true;
    }
    $filename = APPPATH."custom_class/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }else{
        die("ERROR CLASS_NOT_FOUND [$className] : " . __FILE__);
    }
}

spl_autoload_register("autoloadCustomClass");