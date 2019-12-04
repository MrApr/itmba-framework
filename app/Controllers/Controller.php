<?php


namespace App\Controllers;


class Controller
{
    /**
     * Sample Controller with Sample Method
     * @param string $val
     * @throws \Exception
     */
    public function test(string $val)
    {
       return view('test',['title' => $val]);
    }
}