<?php

/**
 * Get Config Values
 * @param $key
 * @return string
 */
function config(string $key)
{
    //The value that should return
    $config_val = null;

    //The return value ! in order to delete and unset included configuration file for memory performance
    $value = null;
    //Explode keys by the key.key.key pattern
    $keys = explode('.',$key);

    //Check if its array and it's countable then start finding value
    if(is_array($keys) && count($keys))
    {
        foreach ($keys as $key => $value)
        {
            //Index 0 is for the file name always
            if($key == 0)
            {
                //If Config file exists, include it
                if(file_exists(APP_ROOT."/config/".$value.".php"))
                {
                    //include config file
                    $config_val = include APP_ROOT."/config/".$value.".php";
                }
            }
            else
            {
                //Check if included file has this key ! if it hast put it in Config_value variable
                if(isset($config_val[$value]))
                {
                    $config_val = $config_val[$value];
                }
            }
        }
        $value = (!is_array($config_val)) ? $config_val : null;
        unset($config_val);
    }

    //Check if config_value is array or not ! if not return value if yes return null
    return $value;
}