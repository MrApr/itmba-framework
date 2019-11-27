<?php


namespace Services\Cores\Commands;


use Services\Cores\Creators\CreatorBase;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModelCommand extends CommandBase
{

    protected $command_name = 'create:model';

    protected $command_description = "Creates empty Model class";

    protected $command_arguments = array('model_name' => 'Defines model name');

    protected $command_options = array('path' => 'Defines custom path under desired section');


    /**
     * Get command configured and run application based on users input
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $model_name = $input->getArgument('model_name');

        $path = (!empty($input->getOption('path'))) ? $input->getOption('path') : null;

        if(!is_string($model_name))
        {
            $output->writeln('Model name should be string');
            die();
        }

        if(!empty($path) && !is_string($path))
        {
            $output->writeln('Path should be string');
            die();
        }

        //Show user message that we are trying to run application
        $output->writeln("Creating Model ".$model_name);

        $creator_base = new CreatorBase($model_name,'Models',
            'protected $connection = \'mysql\';

    protected $table = \'\';

    protected $fillable = [

    ];

    protected $primaryKey = \'id\';',
            'Model',$model_name,[
                '\Illuminate\Database\Eloquent\Model'
            ]);
        $res = $creator_base->createFile();

        if($res['status'] == "success")
        {
            $outputStyle = new OutputFormatterStyle('black', 'green', ['bold', 'blink']);
            $output->getFormatter()->setStyle('fire', $outputStyle);
            $output->writeln('<fire>Model Created Successfully</>');
        }
        else
        {
            $outputStyle = new OutputFormatterStyle('black', 'red', ['bold', 'blink']);
            $output->getFormatter()->setStyle('fire', $outputStyle);
            $output->writeln('<fire>'.$res['message'].'</>');
        }
    }
}