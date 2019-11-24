<?php


namespace Services;


use Dotenv\Dotenv;

/**
 * Sample Service
 * Class EnvStarter
 * @package Services
 */
class EnvStarter implements StarterInterface
{
    /**
     * When Service Registers Constructor gets called
     * EnvStarter constructor.
     */
    public function register()
    {
        $dotenv = Dotenv::create(dirname(dirname(__DIR__)));
        $dotenv->load();
    }
}