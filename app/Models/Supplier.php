<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_code', 'supplier_name','supplier_npwp','supplier_fax','supplier_address','supplier_city','supplier_nation','supplier_person','supplier_phone','supplier_email','remark'];
}
