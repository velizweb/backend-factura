<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryTax extends Model
{
    protected $table = 'country_tax';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'is_active'        => 'string'
    ];

    public function tax(){
    	return $this->belongsTo(EntityMasterdata::class, 'tax_id', 'id');
    }

    public function taxpercentage(){
        return $this->belongsTo(EntityMasterdata::class, 'tax_percentage_id', 'id');
    }

    public function country(){
    	return $this->belongsTo(EntityMasterdata::class, 'country_id', 'id');
    }
    
    public function tax_percentage(){
        return $this->belongsTo(EntityMasterdata::class, 'tax_percentage_id', 'id');
    } 
}
