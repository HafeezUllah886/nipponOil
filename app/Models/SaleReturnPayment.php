<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnPayment extends Model
{
    use HasFactory;
    protected $primaryKey = 'saleReturnPaymentID';
    protected $table = 'saleReturnPayments';
    protected $guarded = [];
    public $timestamps = false;

    public function saleReturn(){
        return $this->belongsTo(\App\Models\SaleReturn::class, 'saleReturnID', 'saleReturnID');
    }
}
