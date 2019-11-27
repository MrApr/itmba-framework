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

    public function createFile()
    {
        $this->configFile();

        if(!file_exists($this->directory.".php"))
        {
            try{
                $file = fopen($this->directory.".php",'w');
            }catch (\Exception $e)
            {
                die('Cannot create directory');
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