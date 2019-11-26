<?php


namespace App\Commands;


use Services\Cores\Commands\CommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends CommandBase
{
    protected $command_name = 'say:greetings';

    protected $command_description = "Says Greetings to Every one";

    protected $command_arguments = array("name" => "Sets name",'family' => 'Sets family');

    protected $command_options = array('hi' => 'says hi','greeting' => 'says greetings');

    public function handle(InputInterface $input, OutputInterface $output)
    {
        $greeting_text = "";

        if(count($this->command_options))
        {
            foreach ($this->command_options as $option_name => $option_descp)
            {
                $option = $input->getOption($option_name);
                switch ($option_name)
                {
                    case "hi":
                        $greeting_text = "Hi ";
                        break;
                    case "greeting":
                        $greeting_text = "Greetings ";
                        break;
                    default:
                        $greeting_text = "Hi ";
                }
            }
        }

        if(count($this->command_arguments))
        {
            foreach ($this->command_arguments as $name => $descp)
            {
                $argument = $input->getArgument($name);

                switch ($name)
                {
                    case "name" && !empty($argument):
                        $greeting_text .= $argument." ";
                        break;
                    case "family" && !empty($argument):
                        $greeting_text .= $argument." ";
                        break;
                }
            }
        }

        $output->writeln($greeting_text);
    }
}