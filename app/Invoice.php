<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    public $timestamps = false;
    protected $guarded = [];

    public function invoiceLine()
    {
        return $this->hasMany(InvoiceLine::class, 'invoice_id', 'id');
    }

    public function invoicePayment()
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id', 'id');
    }

    public function client(){
    	return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function company(){
    	return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function branch(){
    	return $this->belongsTo(BranchOffice::class, 'branch_office_id', 'id');
    }

    public function source_countries(){
        return $this->belongsTo(EntityMasterdata::class, 'source_country', 'id');
    }

    public function destination_countries(){
        return $this->belongsTo(EntityMasterdata::class, 'destination_country', 'id');
    }

    public function seller_countries(){
        return $this->belongsTo(EntityMasterdata::class, 'seller_country', 'id');
    }
}
