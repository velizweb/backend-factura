<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntityMasterdata extends Model
{
    protected $table = 'entity_masterdata';
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = ['code', 'name', 'description', 'field', 'entity_id', 'is_active', 'id_deleted'];

    public function entity(){
    	return $this->belongsTo(Entity::class);
    }

}
