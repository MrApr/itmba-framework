<?php

//Define Commands class in here
return[
    \App\Commands\Command::class,
    \Services\Cores\Commands\ServeCommand::class,
    \Services\Cores\Commands\CreateModelCommand::class,
    \Services\Cores\Commands\CreateCommand::class,
    \Services\Cores\Commands\CreateController::class
];