<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = ['user_id', 'cbd_id','purchase_request_no','tipe','mo','style','destination','department','applicant','time_line','remark'.'status'];

    public function detailrequest()
    {
        return $this->hasMany(PurchaseRequestDetail::class, 'purchase_request_id');
    }
   
}
