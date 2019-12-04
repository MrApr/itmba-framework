<?php

/**
 *Resolve a container to use it globally
 */
$container = new \Illuminate\Container\Container();

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

/**
 * Calling views to in order to return them
 * @param string $view_name
 * @param array $data
 * @throws Exception
 */
function view(string $view_name,array $data =[])
{
    if(!file_exists(BLADE_VIEW_PATH.'/'.$view_name.'.blade.php'))
    {
        die('View not found');
    }
    $blade= new \eftec\bladeone\BladeOne(BLADE_VIEW_PATH,BLADE_CACHE_PATH,\eftec\bladeone\BladeOne::MODE_AUTO);

    echo $blade->run($view_name,$data);
}

/**
 * Getting a route based on route name
 * @param string $name
 * @return string|array
 */
function route(string $name)
{
    global $container;

    $routes = $container->make('routes');
    $route = $routes->has($name);
    if(!empty($route) && is_array($route) && count($route))
    {
        $route = $routes->makeRoute($route);
    }

    return $route;
}