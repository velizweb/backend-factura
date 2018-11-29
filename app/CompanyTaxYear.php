<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyTaxYear extends Model
{
    protected $table = 'company_tax_year';
    public $timestamps = false;
    protected $guarded = [];

	public function company(){
    	return $this->belongsTo(Company::class);
    }
}