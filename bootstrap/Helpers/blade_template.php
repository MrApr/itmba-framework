<?php

function view(string $view_name,array $data =[])
{
    if(!file_exists(BLADE_VIEW_PATH.'/'.$view_name.'.blade.php'))
    {
        die('View not found');
    }
    $blade= new \eftec\bladeone\BladeOne(BLADE_VIEW_PATH,BLADE_CACHE_PATH,\eftec\bladeone\BladeOne::MODE_AUTO);

    echo $blade->run($view_name,$data);
}