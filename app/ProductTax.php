<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTax extends Model
{
    protected $table = 'product_tax';
    public $timestamps = false;
    protected $guarded = [];

    public function product(){    	
    	return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function countrytax(){
    	return $this->belongsTo(CountryTax::class, 'country_tax_id', 'id');
    }
}
