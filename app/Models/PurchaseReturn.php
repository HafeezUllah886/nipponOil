<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;
    protected $primaryKey = 'purchaseReturnID';
    protected $table = 'purchaseReturns';
    protected $guarded = [];
    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'supplierID', 'accountID');
    }

    public function purchase()
    {
        return $this->belongsTo(\App\Models\Purchase::class, 'purchaseID', 'purchaseID');
    }

    public function purchaseReturnDetails()
    {
        return $this->hasMany(\App\Models\PurchaseReturnDetail::class, 'purchaseReturnID', 'purchaseReturnID');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'productID', 'productID');
    }

    public function purchaseReturnPayments()
    {
        return $this->hasMany(\App\Models\PurchaseReturnPayments::class, 'purchaseReturnID', 'purchaseReturnID');
    }



}
