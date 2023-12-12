<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnPayments extends Model
{
    use HasFactory;
    protected $primaryKey = 'purchaseReturnPaymentID';
    protected $table = 'purchaseReturnPayments';
    protected $guarded = [];
    public $timestamps = false;

    public function purchaseReturn()
    {
        return $this->belongsTo(\App\Models\PurchaseReturn::class, 'purchaseReturnID', 'purchaseReturnID');
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'accountID', 'accountID');
    }
}
