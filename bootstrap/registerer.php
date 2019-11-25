<?php
//Load Services that are registered in services Config
$services = require_once "../config/services.php";

//Register all files that contain functions
foreach (glob(__DIR__."/Helpers/*") as $helper_file)
{
    //Check if helper file exists
    if(file_exists($helper_file))
    {
        //Include Helper files
        include_once $helper_file;
    }
}

//Check if any service exists to register
if(count($services))
{
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
            //Initialise desired service
            $key = new $service();
        }
        else
        {
            //If no alias provided only register that service
            $key = new $service();
            $key->register();
        }

        //If class has register method
        if(method_exists($key,'register'))
        {
            //register it
            $key->register();
        }

        //clean up ram
        unset($key,$service);
    }
}