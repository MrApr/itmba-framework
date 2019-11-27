<?php


namespace Services;

use Symfony\Component\Console\Application;

class CommandsStarter
{
    /**
     * Loading commands that are defined in commands config
     * register them to $application
     * @param Application $application
     * @return boolean
     */
    public function RegisterCommands(Application $application)
    {
        //Check if config command file exists
        if(file_exists(dirname(dirname(__DIR__)))."/config/commands.php")
        {
            //Require them
            $commands = require_once dirname(dirname(__DIR__))."/config/commands.php";

            //Check if any commands are defined or not
            if(count($commands))
            {
                //Register loaded commands them
                foreach ($commands as $command)
                {
                    //Register commands
                    $application->add(new $command);
                }
                //Return success to register

                return true;
            }
        }
        //register false to not register
        return false;
    }

    public function requireHelpers()
    {
        //Register all files that contain functions
        foreach (glob(dirname(dirname(__DIR__))."/bootstrap/Helpers/*") as $helper_file)
        {
            //Check if helper file exists
            if(file_exists($helper_file))
            {
                //Include Helper files
                include_once $helper_file;
            }
        }
    }

    /**
     * //Rung and register commands
     * @throws \Exception
     */
    public function run()
    {
        //Make instance of app
        $application = new Application();

        //Requiring App Helpers
        $this->requireHelpers();

        //Register Commands
        $res = $this->RegisterCommands($application);

        //if res was success run symfony cli application
        if($res)
        {
            $application->run();
        }
    }
}