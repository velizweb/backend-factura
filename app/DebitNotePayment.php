<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DebitNotePayment extends Model
{
    protected $table = 'debitnote_payment';
    public $timestamps = false;
    protected $guarded = [];

    public function taxDocument()
    {
        return $this->belongsTo(TaxDocument::class, 'id');
    }

}