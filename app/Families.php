<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Families extends Model
{
    protected $table = 'families';
    public $timestamps = false;
    protected $fillable = ['id','name','company_id','is_active', 'is_deleted'];
    protected $guarded = [];


    public function company(){
    	return $this->belongsTo(Company::class);
    }
}
