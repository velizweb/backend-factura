<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseProduct extends Model
{
    protected $table = 'warehouse_product';
    public $timestamps = false;
    protected $fillable = ['company_id','branch_id','warehouse_id', 'product_id','quantity'];
    protected $guarded = [];

    public function company()
    {
    	return $this->belongsTo(Company::class);
    }

    public function warehouse()
    {
    	return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function branch()
    {
        return $this->belongsTo(BranchOffice::class, 'id', 'branch_id');
    }
}
