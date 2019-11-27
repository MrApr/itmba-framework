<?php


namespace Services\Cores\Commands;


use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends CommandBase
{
    protected $command_name = 'start';

    protected $command_description = "Starts php application";

    protected $command_arguments = array();

    protected $command_options = array('port' => 'Input port number','root' => 'Add root directory');

    protected $start_command = null;

    protected $port = 8000;

    protected $root = 'public';

    /**
     * Config Starting Command
     * @param InputInterface $input
     */
    public function prepareURl(InputInterface $input)
    {
        //If it has defined change default port
        if($input->getOption('port') && !empty($input->getOption('port')) && is_numeric($input->getOption('port')))
        {
            $this->port = $input->getOption('port');
        }

        //If user has Root Directory defined change root directory
        if($input->getOption('root') && !empty($input->getOption('root')) && is_string($input->getOption('root')))
        {
            $this->root = $input->getOption('root');
        }

        // Configure command based on entered user options
        $this->start_command = "php -S localhost:{$this->port} -t {$this->root}/";
    }

    /**
     * Get command configured and run application based on users input
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {

        //Show user message that we are trying to run application
        $output->writeln("Starting Application ...");
        //Configure start command string
        $this->prepareURl($input);
        //Show user the ports and root directory then run application
        $output->writeln("Application will be start on port {$this->port} AND root {$this->root}");

        //Check if command shell functions exist then run them
        if(function_exists('shell_exec'))
        {
            shell_exec($this->start_command);
        }
        elseif (function_exists('system'))
        {
            system($this->start_command);
        }
        elseif (function_exists('passthru'))
        {
            passthru($this->start_command);
        }
        elseif (function_exists('exec'))
        {
            exec($this->start_command);
        }
        else
        {
            $outputStyle = new OutputFormatterStyle('black', 'red', ['bold', 'blink']);
            $output->getFormatter()->setStyle('fire', $outputStyle);
            $output->writeln('<fire>Cannot Run TMBA start</>');
            $output->writeln('<fire>Please install desired modules like shell_exec</>');
        }
    }
}