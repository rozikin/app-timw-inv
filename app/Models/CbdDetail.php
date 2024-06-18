<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbdDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'color_code',
        'color',
        'size_code',
        'size',
        'qty'
    ];


     // Define the inverse of the relationship with Cbd
     public function cbd()
     {
         return $this->belongsTo(Cbd::class, 'order_no', 'order_no');
     }
}
