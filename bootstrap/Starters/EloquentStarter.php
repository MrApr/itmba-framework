<?php


namespace Services;

use Illuminate\Database\Capsule\Manager as Capsule;
class EloquentStarter implements StarterInterface
{
    public function register()
    {
        $capsule = new Capsule;

        $capsule->addConnection([

            "driver" => "mysql",

            "host" =>   config('database.host'),

            "database" => config('database.db'),

            "username" => config('database.username'),

            "password" => config('database.password')
        ]);

        //Make this Capsule instance available globally.
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM.
        $capsule->bootEloquent();
    }
}