<?php


namespace Services\Cores\Commands;


use Services\Cores\Creators\CreatorBase;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Services\Cores\Commands\CommandBase as CommandBase;

class CreateController extends CommandBase
{

    /**
     * Defines Command name
     * @var string
     */
    protected $command_name = 'create:controller';

    /**
     * Defines Command's description
     * @var string
     */
    protected $command_description = "Creates empty Controller class";

    /**
     * Defines command Arguments Should be array and Key=> value !
     * Key is argument name and value is argument description
     * @var array
     */
    protected $command_arguments = array('controller_name' => 'Defines controller name');

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
        $controller_name = $input->getArgument('controller_name');

        $path = (!empty($input->getOption('path'))) ? $input->getOption('path') : null;

        if(!is_string($controller_name))
        {
            $output->writeln('controller name should be string');
            die();
        }

        if(!empty($path) && !is_string($path))
        {
            $output->writeln('Path should be string');
            die();
        }

        //Show user message that we are trying to run application
        $output->writeln("Creating controller ".$controller_name);

        $creator_base = new CreatorBase($controller_name,'Controllers',
            '    /**
     * Controller with index Method
     * @param string $val
     * @throws \Exception
     */
    public function index()
    {
       //Codes here
    }',
            ' ',$controller_name,["Illuminate\Http\Request"]);
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