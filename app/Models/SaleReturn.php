<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;
    protected $primaryKey = 'saleReturnID';
    protected $table = 'saleReturns';
    protected $guarded = [];

    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'accountID', 'accountID');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Account::class, 'customerID', 'accountID');
    }

    public function saleReturnDetails(){
        return $this->hasMany(\App\Models\SaleReturnDetail::class, 'saleReturnID', 'saleReturnID');
    }

    public function saleReturnPayments(){
        return $this->hasMany(\App\Models\SaleReturnPayment::class, 'saleReturnID', 'saleReturnID');
    }


}
