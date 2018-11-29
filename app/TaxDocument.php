<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxDocument extends Model
{
    protected $table = 'tax_document';
    public $timestamps = false;
    protected $guarded = [];

	public function branch(){        
        return $this->hasOne(BranchOffice::class, 'id', 'branch_office_id');
    }    

    public function documentLine(){
        return $this->hasMany(TaxDocumentLine::class, 'tax_document_id', 'id');
    }

    public function creditNoteLine(){
        return $this->hasMany(CreditNoteDetail::class, 'taxdocument_id', 'id');    
    }

    public function emissiontype(){
        return $this->hasMany(EntityMasterdata::class, 'emission_type', 'id');
    }

	public function environmenttype(){
        return $this->hasMany(EntityMasterdata::class, 'environment_type', 'id');
    }    

    public function supplier(){
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function document_type(){
        return $this->hasMany(EntityMasterdata::class, 'document_type_id', 'id');
    }

    public function dispatcher(){
        return $this->hasOne(Dispatcher::class, 'id', 'dispatcher_id');
    }

    public function debitPayment()
    {
        return $this->hasMany(DebitNotePayment::class, 'tax_document_id', 'id');
    }

}
