<?php
/*
 * Requiring Register to register Services
 */
require_once ("registerer.php");

require_once ("../routes/web.php");

$container->instance('routes',$router);

$router->execute();

/*$text = '/test/{param}/route/{param2}/index';
$url = '/test/param1/route/param2/index';

$text = preg_replace('/{(.*?)}/','*',$text);
$text = str_replace('/','\/',$text);
$text = str_replace('*','(.*?)',$text);
preg_match_all("/".$text."/",$url,$matches);
print_r($matches);*/

/*if(fnmatch($text,$url))
{
    echo "hi";
}*/