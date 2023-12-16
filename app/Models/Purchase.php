<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $primaryKey = 'purchaseID';
    protected $table = 'purchases';
    protected $guarded = [];



    public function purchaseOrders()
    {
        return $this->hasMany(\App\Models\PurchaseOrder::class, 'purchaseID', 'purchaseID');
    }

    public function purchaseReturns()
    {
        return $this->hasMany(\App\Models\PurchaseReturn::class, 'purchaseID', 'purchaseID');
    }

    public function purchasePayments()
    {
        return $this->hasMany(\App\Models\PurchasePayment::class, 'purchaseID', 'purchaseID');
    }

    public function purchaseStatus()
    {
        return $this->belongsTo(\App\Models\PurchaseStatus::class, 'purchaseStatusID', 'purchaseStatusID');
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'supplierID', 'accountID');
    }

    public function purchaseReceive()
    {
        return $this->hasMany(PurchaseReceive::class, 'purchaseID', 'purchaseID');
    }
}

