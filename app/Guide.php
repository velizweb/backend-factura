<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    protected $table = 'guide';
    public $timestamps = false;
    protected $guarded = [];
}
