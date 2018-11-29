<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditNoteDetail extends Model
{
    protected $table = 'creditnote_detail';
    public $timestamps = false;
    protected $guarded = [];

    public function taxDocument()
    {
        return $this->belongsTo(TaxDocument::class, 'id');
    }

}

