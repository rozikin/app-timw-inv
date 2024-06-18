<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cbd extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'supplier_raw_material_code',
        'item',
        'sample_code',
    
    ];

    // Define the one-to-many relationship with CbdDetail
    public function details()
    {
        return $this->hasMany(CbdDetail::class, 'order_no', 'order_no');
    }
}
