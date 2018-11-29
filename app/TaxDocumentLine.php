<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxDocumentLine extends Model
{
    protected $table = 'tax_document_line';
    public $timestamps = false;
    protected $guarded = [];

    public function taxDocument()
    {
        return $this->belongsTo(TaxDocument::class, 'id');
    }
}
