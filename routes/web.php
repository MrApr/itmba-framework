<?php

$router = new \Services\Router();

$router->get('/test/home','marketing@test');
$router->get('/test/home','marketing@test');
$router->get('/test/home','marketing@test')->name("Test_Route");
$router->get('/test/home','marketing@test');
$router->POST('/test/home','marketing@test')->name('test');
$router->prefix('real')->middleware('TestMiddleware')->group(function () use ($router){
    $router->get('/test2','marketing@test');
    $router->get('show/{param}/test3','Controller@test')->name("test2");
});
$router->get('/test/home','marketing@test');
$router->get('/test/{value}','marketing@test');
