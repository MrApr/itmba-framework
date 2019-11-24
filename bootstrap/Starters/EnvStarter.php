<?php


namespace Services;


use Dotenv\Dotenv;

/**
 * Sample Service
 * Class EnvStarter
 * @package Services
 */
class EnvStarter
{
    /**
     * When Service Registers Constructor gets called
     * EnvStarter constructor.
     */
    public function __construct()
    {
        $dotenv = Dotenv::create(dirname(dirname(__DIR__)));
        $dotenv->load();
    }
}