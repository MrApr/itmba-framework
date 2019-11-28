<?php


namespace Services\Cores\Commands;


use Services\Cores\Creators\CreatorBase;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Services\Cores\Commands\CommandBase as CommandBase;

class CreateCommand extends CommandBase
{

    /**
     * Defines Command name
     * @var string
     */
    protected $command_name = 'create:command';

    /**
     * Defines Command's description
     * @var string
     */
    protected $command_description = "Creates empty Command class";

    /**
     * Defines command Arguments Should be array and Key=> value !
     * Key is argument name and value is argument description
     * @var array
     */
    protected $command_arguments = array('command_name' => 'Defines command name');

    /**
     * Defines command Option Should be array and Key=> value !
     * Key is option name and value is argument description
     * @var array
     */
    protected $command_options = array('path' => 'Defines custom path under desired section');


    /**
     * Create Command based on users input
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $command_name = $input->getArgument('command_name');

        $path = (!empty($input->getOption('path'))) ? $input->getOption('path') : null;

        if(!is_string($command_name))
        {
            $output->writeln('Command name should be string');
            die();
        }

        if(!empty($path) && !is_string($path))
        {
            $output->writeln('Path should be string');
            die();
        }

        //Show user message that we are trying to run application
        $output->writeln("Creating Command ".$command_name);

        $creator_base = new CreatorBase($command_name,'Commands',
            '/**
     * Defines Command name
     * @var string
     */
    protected $command_name = \'name:command\';

    /**
     * Defines Command\'s description
     * @var string
     */
    protected $command_description = "Command Description";

    /**
     * Defines command Arguments Should be array and Key=> value !
     * Key is argument name and value is argument description
     * @var array
     */
    protected $command_arguments = array(\'key\' => \'value\');

    /**
     * Defines command Option Should be array and Key=> value !
     * Key is option name and value is argument description
     * @var array
     */
    protected $command_options = array(\'key\' => \'value\');


    /**
     * Create Command based on users input
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        //Codes to get executed during handle 
    }',
            'CommandBase',$command_name,[
                'Symfony\Component\Console\Input\InputInterface',
                'Symfony\Component\Console\Output\OutputInterface',
                'Services\Cores\Commands\CommandBase'
            ]);
        $res = $creator_base->createFile();

        if($res['status'] == "success")
        {
            $outputStyle = new OutputFormatterStyle('black', 'green', ['bold', 'blink']);
            $output->getFormatter()->setStyle('fire', $outputStyle);
            $output->writeln('<fire>Command Created Successfully</>');
        }
        else
        {
            $outputStyle = new OutputFormatterStyle('black', 'red', ['bold', 'blink']);
            $output->getFormatter()->setStyle('fire', $outputStyle);
            $output->writeln('<fire>'.$res['message'].'</>');
        }
    }
}