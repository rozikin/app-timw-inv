<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['item_code', 'item_name','description','category_id','unit_id','remark'];

    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function purchaseRequestDetails()
    {
        return $this->hasMany(PurchaseRequestDetail::class);
    }

    public function materialInDetails()
    {
        return $this->hasMany(MaterialInDetail::class, 'item_code', 'item_code');
    }


    
}
