<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    public $timestamps = false;
    protected $fillable = ['name', 'principal_code', 'auxiliary_code', 'description', 'generic', 'unit_price', 'unit_cost', 'min_stock', 'max_stock','company_id', 'location', 'laboratory', 'expired_date', 'is_purchase_active', 'is_sale_active', 'is_active', 'is_deleted'];
    protected $guarded = [];


    // protected $casts = [
    //     'is_sale_active' 		=> 'string',
    //     'is_purchase_active' 	=> 'string',
    //     'is_active'				=> 'string',
    //     'is_deleted'			=> 'string'
    // ];

    public function producttax(){
        return $this->hasMany(ProductTax::class, 'product_id', 'id');
    }

    public function warehouseProduct()
    {
        return $this->hasMany(WarehouseProduct::class);
    }

}
