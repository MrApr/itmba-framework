<?php


namespace Services;


use Illuminate\Http\Request;

/**
 * Sample Service
 * Class EnvStarter
 * @package Services
 */
class RequestsStarter implements StarterInterface
{
    /**
     * When Service Registers Constructor gets called
     * EnvStarter constructor.
     */
    public function register()
    {
        $request = Request::capture();

        return [
            'instance' => "Illuminate\Http\Request",
            'value' => $request
        ];
    }
}