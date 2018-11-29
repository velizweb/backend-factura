<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $table = 'invoice_payment';
    public $timestamps = false;
    protected $guarded = [];

    public function invoice(){
    	return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function paymenttype(){
    	return $this->belongsTo(EntityMasterdata::class, 'payment_type_id', 'id');
    }

    public function bank(){
    	return $this->belongsTo(EntityMasterdata::class, 'bank_id', 'id');
    }

    public function unittime(){
    	return $this->belongsTo(EntityMasterdata::class, 'unit_time_id', 'id');
    }

}
