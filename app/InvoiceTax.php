<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceTax extends Model
{
    protected $table = 'invoice_tax';
    public $timestamps = false;
    protected $guarded = [];

	public function invoice(){
    	return $this->hasMany(Invoice::class, 'invoice_id', 'id');
    }

    public function countrytax(){
    	return $this->hasMany(CountryTax::class, 'country_tax_id', 'id');
    }

}