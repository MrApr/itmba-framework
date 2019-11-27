<?php


namespace Services\Cores\Creators;


class CreatorBase
{
    private $directory = APP_ROOT."/app/";

    private $class_name;

    private $src;

    private $section;

    private $extends;

    private $traits;

    private $contents;

    private $file_contents = '<?php

    
namespace App\{section}\{src};

{traits}

class {class_name} {extends}
{
    {contents}
}

';

    /**
     * CreatorBase constructor.| configs file creator class and fills desired fields
     * @param string $class_name
     * @param string $section
     * @param $contents
     * @param string|null $extends
     * @param null $src
     * @param array $traits
     */
    public function __construct(string $class_name,string $section, $contents, string $extends = null ,$src = null ,array $traits = [])
    {
        $this->class_name = $class_name;
        $this->directory .= $section."/";
        $this->section = $section;
        $this->contents = $contents;

        if(!empty($extends))
        {
            $this->extends = $extends;
        }

        if(count($traits))
        {
            $this->traits = $traits;
        }

        if(!empty($src))
        {
            $this->src = $src;
            $this->directory .= $src;
        }
    }

    /**
     * Config Creator command and replaces user inputs to available Place-holders
     */
    public function configFile()
    {
        $this->file_contents = str_replace([
            '{class_name}', '{section}', '{contents}'
        ],[
            $this->class_name, $this->section, $this->contents
        ],$this->file_contents);

        if(!empty($this->extends))
        {
            $this->file_contents = str_replace('{extends}','extends '.$this->extends,$this->file_contents);
        }

        if(count($this->traits))
        {
            foreach ($this->traits as $trait)
            {
                $this->file_contents = str_replace('{traits}','use '.$trait.";\n"."{traits}",$this->file_contents);
            }
            $this->file_contents = str_replace("\n"."{traits}",'',$this->file_contents);
        }

        if(!empty($this->src))
        {
            $this->file_contents = str_replace('{src}',$this->src,$this->file_contents);
        }
    }

    /**
     * Configs file and creates file based on users input and returns success or fail
     * @return array
     */
    public function createFile()
    {
        $this->configFile();

        if(!file_exists($this->directory.".php"))
        {
            try{
                $file = fopen($this->directory.".php",'w');
            }catch (\Exception $e)
            {
                return array('status' => 'error','message' => "Cannot create Directory with error ".$e->getMessage());

            }

            fwrite($file,$this->file_contents);
            fclose($file);
            return array('status' => 'success','message' => "File Created");
        }
        else
        {
            return array('status' => 'error','message' => "File Exists Already");
        }
    }
}