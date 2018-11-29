<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorrelativeDocument extends Model
{
    protected $table = 'correlative_document';
    public $timestamps = false;
    protected $guarded = [];

    public function documenttype(){
    	return $this->belongsTo(EntityMasterdata::class, 'document_type_id', 'id');
    }

}
