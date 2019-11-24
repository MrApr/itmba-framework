<?php
//Load Services that are registered in services Config
$services = require_once "../config/services.php";
//Registering Services
foreach ($services as $key => $service)
{
    //Check if there is an alias provided register that service with entered key
    if(is_string($key))
    {
        //IF No class founds Abort application
        if(!class_exists($service))
        {
            die('Class not found to register');
        }
        //Register desired service
        $key = new $service();
    }
    else
    {
        //If no alias provided only register that service
        new $service();
    }
}