<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialInDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_in_id', 
        'purchase_order_id', 
        'item_id', 
        'color', 
        'size', 
        'qty', 
        'batch', 
        'no_roll', 
        'gw', 
        'nw', 
        'width', 
        'gramasi', 
        'mo', 
        'style', 
        'rak_id', 
        'remark', 
        'satus'
    ];

    public function materialIn()
    {
        return $this->belongsTo(MaterialIn::class, 'material_in_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function purchaseorder(){
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}


