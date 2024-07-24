<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIn extends Model
{
    use HasFactory;


    protected $fillable = [
        'material_in_no', 
   
        'supplier_id', 
        'no_sj', 
        'received_by', 
        'location', 
        'courier', 
        'remark', 
        'status', 
        'user_id'
    ];

    public function details()
    {
        return $this->hasMany(MaterialInDetail::class, 'material_in_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
