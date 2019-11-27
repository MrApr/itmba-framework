<?php


namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $connection = 'mysql';

    protected $table = '';

    protected $fillable = [

    ];

    protected $primaryKey = 'id';
}