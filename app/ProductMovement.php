<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMovement extends Model
{
    protected $table = 'product_movements';
    protected $fillable = ['company_id','branch_origin', 'warehouse_origin', 'destination_branch', 'destination_store', 'product_id', 'quantity_origin', 'target_amount', 'amount_send', 'current_origin_quantity', 'current_destination_quantity', 'document_number', 'supplier', 'invoiceNumber', 'invoiceAmount', 'observation', 'expiration_date', 'created_at', 'updated_at', 'reception_date', 'types'];
    protected $guarded = [];

    public function company()
    {
    	return $this->belongsTo(Company::class);
    }

    public function warehouse_origin()
    {
    	return $this->hasOne(Warehouse::class, 'id', 'warehouse_origin');
    }

    public function destination_store()
    {
    	return $this->hasOne(Warehouse::class, 'id', 'destination_store');
    }

    public function branch_origin()
    {
        return $this->hasOne(BranchOffice::class, 'id', 'branch_origin');
    }

    // public function destination_b()
    // {
    //     return $this->hasOne(BranchOffice::class, 'id', 'destination_branch');
    // }

    public function product()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier');
    }

    public function getCreatedAtAttribute($value)
    {
        return date("d-m-Y h:i:s a", strtotime($value));
    }

    public function getExpirationDateAttribute($value)
    {
        return date("d-m-Y", strtotime($value));
    }

    public function getReceptionDateAttribute($value)
    {
        return date("d-m-Y h:i:s a", strtotime($value));
    }

    public function getTypesAttribute($value)
    {
        switch ($value) {
            case '1':
                $value = "Compra";
                break;

            case '2':
                $value = "Factura";
                break;

            case '4':
                $value = "Vencimiento";
                break;

            case '5':
                $value = "Donación";
                break;

            case '6':
                $value = "Devolución";
                break;

            case '8':
                $value = "Auditoria";
                break;

            case '9':
                $value = "Auditoria";
                break;
        }

        return $value;
    }
}
