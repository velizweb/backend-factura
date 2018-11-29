<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $table = 'entity';
    public $timestamps = false;
    protected $guarded = [];
}
