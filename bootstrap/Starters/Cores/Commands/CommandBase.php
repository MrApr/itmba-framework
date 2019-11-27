<?php


namespace Services\Cores\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CommandBase extends Command
{
    protected $command_name = 'command_group:command';
    protected $command_description = "Says hello to Every one";

    protected $command_arguments = array("name" => "description");

    protected $command_options = array('name' => 'description');

    public function configure()
    {
        //Setting Command name
        $this->setName($this->command_name);
        //Setting Command Description
        $this->setDescription($this->command_description);

        //Setting Command Input Arguments like
        if(count($this->command_arguments))
        {
            foreach ($this->command_arguments as $arg_name => $arg_description)
            {
                $this->addArgument($arg_name,InputArgument::REQUIRED, $arg_description);
            }
        }

        //Setting Command Options
        if(count($this->command_options))
        {
            foreach ($this->command_options as $option_name => $option_description)
            {
                $this->addOption($option_name,null,InputOption::VALUE_OPTIONAL,$option_description);
            }
        }
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        //Call handle method and execute the program
        $this->handle($input,$output);
        return 1;
    }

    abstract public function handle(InputInterface $input, OutputInterface $output);
}