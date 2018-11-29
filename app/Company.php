<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = ['name', 'comercial_name', 'ruc', 'special_code', 'emission_code', 'tax_year', 'url', 'phone', 'address', 'logo', 'digital_certificate', 'digital_certificate_pass', 'environment_type', 'emission_type', 'email', 'register_number', 'is_artisan', 'is_accounting', 'is_active'];

    public function emissiontype(){
    	return $this->belongsTo(EntityMasterdata::class, 'emission_type', 'id');
    }

    public function environmenttype(){
    	return $this->belongsTo(EntityMasterdata::class, 'environment_type', 'id');
    }

    public function warehouseProduct()
    {
        return $this->hasMany(WarehouseProduct::class);
    }

}
