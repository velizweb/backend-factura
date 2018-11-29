<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryCurrency extends Model
{
    protected $table = 'country_currency';
    public $timestamps = false;
    protected $guarded = [];
}
