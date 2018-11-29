<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    protected $table = 'system_configuration';
    public $timestamps = false;
    protected $guarded = [];
}
