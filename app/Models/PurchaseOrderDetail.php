<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'item_id',
        'color',
        'size',
        'qty',
        'price',
        'total_price',
        'status',
        'remark',
    ];


     // Relationship methods

     public function purchaseOrder()
     {
         return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
     }
 
     public function item()
     {
         return $this->belongsTo(Item::class, 'item_id');
     }
}
