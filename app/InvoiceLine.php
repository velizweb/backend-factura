<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $table = 'invoice_line';
    public $timestamps = false;
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'id');
    }

    public function product()
    {
    	return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
