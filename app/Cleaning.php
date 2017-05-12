<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cleaning extends Model
{
    //
    protected $connection = 'kanjihybrid-db';
    protected $table = 'cleaning';
    public $timestamps = false;
}
