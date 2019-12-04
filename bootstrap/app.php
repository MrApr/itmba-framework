<?php
/*
 * Requiring Register to register Services
 */
require_once ("registerer.php");

require_once ("../routes/web.php");

$container->instance('routes',$router);

echo route('test2');