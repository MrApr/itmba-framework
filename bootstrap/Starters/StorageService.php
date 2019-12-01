<?php


namespace Services;


use Services\Cores\Exceptions\StorageException;

class StorageService
{

    public $file_name;

    public $file_path;


    public function prePareFileInfo(string $file_path,string $file_name = null)
    {
        if(substr($file_path,-strlen($file_path)) === "/")
        {
            $file_path = substr($file_path,-strlen($file_path));
        }

        $file = APP_ROOT."/storage/".$file_path;

        if(!empty($file_name))
        {
            mkdir($file);
            $file .= "/".$file_name;
        }

        return $file;
    }

    public static function has(string $file_path)
    {
        if(file_exists(APP_ROOT."/storage/".$file_path))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function store(string $file_name, $file)
    {

    }

    public static function makeFile(string $file_directory, string $file_name, string $file_contents)
    {
        $file = self::prePareFileInfo($file_directory,$file_name);

        self::checkFileExists($file);

        $file = fopen($file,'w');
        fwrite($file,$file_contents);
        fclose($file);
    }

    public static function updateFile(string $file_directory,string $contents)
    {

        $file = self::prePareFileInfo($file_directory);

        self::checkFileExists($file,false);

        $file = fopen($file,'w+');
        fwrite($file,$contents);
        fclose($file);
    }

    public static function deleteFile(string $file_directory)
    {
        $file = self::prePareFileInfo($file_directory);

        self::checkFileExists($file,false);

        unlink($file);
    }

    public function checkFileExists(string $location,bool $existence = true)
    {
        if(file_exists($location) && $existence)
        {
            throw new StorageException('File Already Exists','500');
        }
        elseif(!$existence && !file_exists($location))
        {
            throw new StorageException('File Doesnt Exists','500');
        }
    }
}