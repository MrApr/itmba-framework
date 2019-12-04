<?php


namespace App\Controllers;


use Illuminate\Http\Request;

class Controller
{
    /**
     * Sample Controller with Sample Method
     * @param string $val
     * @throws \Exception
     */
    public function test(Request $request, string $val)
    {
       return view('test',['title' => $request->title]);
    }
}