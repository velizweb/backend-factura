<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plan';
    public $timestamps = false;
    protected $fillable = ['name','document_count','duration','price','is_active', 'is_deleted','tester'];
    protected $guarded = [];
}
