<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelaxReturnDetail extends Model
{
    use HasFactory;


    Protected $fillable = [
        'relax_return_id',
        'original_no',
        'item_code',
        'color_code',
        'color_name',
        'size',
        'qty',
        'mo',
        'rak',
        'style',
        'note',
    ];


    public function relaxReturn()
    {
        return $this->belongsTo(RelaxReturn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }

    public function materialInDetail()
    {
        return $this->belongsTo(QRCode::class, 'original_no', 'original_no');
    }

    public function materialInDetailx()
    {
        return $this->belongsTo(materialInDetail::class, 'original_no', 'original_no');
    }

    public function QRCode()
    {
        return $this->belongsTo(QRCode::class, 'original_no', 'original_no');
    }
}