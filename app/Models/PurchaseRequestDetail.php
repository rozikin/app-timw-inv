<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = ['purchase_request_id', 'item_id','color','size','qty','consumtion','allowance','total','status','remark'];
    public function purchaserequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

}
